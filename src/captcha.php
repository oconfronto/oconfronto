<?php
/**
 * Runic Captcha System
 * A simple session-based captcha system using rune emojis for RPG authentication
 * 
 * @version 1.2
 */

include(__DIR__ . "/lib.php");
define("PAGENAME", "Captcha");

try {
    $acc = check_acc($db);

    include_once __DIR__ . "/classes/RunicCaptcha.php";
    $captcha = new RunicCaptcha();

    // Handle AJAX requests if any
    if (isset($_POST['action'])) {
        header('Content-Type: application/json');
        
        try {
            switch ($_POST['action']) {
                case 'check_status':
                    $status = $captcha->needsCaptcha();
                    echo json_encode($status);
                    break;
                    
                case 'generate':
                    $captchaData = $captcha->generateCaptcha();
                    echo json_encode($captchaData);
                    break;
                    
                case 'validate':
                    $answer = filter_input(INPUT_POST, 'answer', FILTER_VALIDATE_INT);
                    if ($answer === false) {
                        echo json_encode(['success' => false, 'message' => 'Entrada inválida']);
                    } else {
                        $result = $captcha->validateAnswer($answer);
                        echo json_encode($result);
                    }
                    break;
                    
                default:
                    echo json_encode(['error' => 'Ação inválida']);
            }
        } catch (Exception $e) {
            error_log("Erro no captcha AJAX: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Erro ao processar sua solicitação. Por favor, tente novamente.'
            ]);
        }
        exit;
    }

    // Check if user is banned
    $captchaStatus = $captcha->needsCaptcha();
    if (isset($captchaStatus['is_banned']) && $captchaStatus['is_banned']) {
        // Show ban message
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Conta Bloqueada - O Confronto</title>
            <meta content="width=device-width, initial-scale=1" name="viewport" />
            <meta charset="UTF-8">
            <link rel="stylesheet" href="static/css/styles.css">
            <style>
                body {
                    background-color: #FAF0E3;
                    font-family: monospace;
                    width: 100dvw;
                    height: 100dvh;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }

                .ban-container {
                    max-width: 400px;
                    margin: 20px auto;
                    text-align: center;
                    padding: 20px;
                    background: rgba(0, 0, 0, 0.8);
                    border: 2px solid #ff0000;
                    border-radius: 5px;
                    color: #fff;
                }
            </style>
        </head>
        <body>
            <div class="ban-container">
                <h3>Conta Temporariamente Bloqueada</h3>
                <p>Sua conta está temporariamente bloqueada devido a falhas na verificação de segurança.</p>
                <p>Tempo restante: <?php echo $captchaStatus['time_left']; ?></p>
                <p>Por favor, aguarde o tempo indicado antes de tentar novamente.</p>
            </div>
        </body>
        </html>
        <?php
        exit;
    }

    // If user doesn't need captcha, redirect to home
    if (!$captchaStatus['needs_captcha']) {
        header('Location: home.php');
        exit;
    }

    // Use a simpler header for the captcha page
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Verificação de Segurança - O Confronto</title>
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta charset="UTF-8">
        <link rel="stylesheet" href="static/css/styles.css">
        <style>
            body {
                background-color: #2b2b2b;
                font-family: monospace;
                width: 100dvw;
                height: 100dvh;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0;
                padding: 20px;
                box-sizing: border-box;
            }

            .captcha-container {
                max-width: 500px;
                width: 100%;
                margin: 20px auto;
                text-align: center;
                padding: 25px;
                background: #f4e4bc;
                border: 3px solid #8b4513;
                border-radius: 8px;
                color: #4a2f1d;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
                position: relative;
                background-image: url('data:image/png;base64,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');
            }

            .captcha-container::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(244, 228, 188, 0.3);
                pointer-events: none;
                border-radius: 5px;
            }

            h3 {
                color: #8b4513;
                font-size: 1.5em;
                margin-bottom: 20px;
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            }

            .runes-display {
                display: flex;
                justify-content: center;
                gap: 15px;
                margin: 20px 0;
                background: white;
                padding: 20px;
                border-radius: 8px;
                border: 2px solid #8b4513;
                font-size: 2.2em;
            }

            .rune-options {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
                margin: 20px 0;
            }

            .rune-option {
                padding: 12px;
                border: 2px solid #8b4513;
                cursor: pointer;
                transition: all 0.3s;
                background: rgba(139, 69, 19, 0.1);
                font-size: 1.1em;
                color: #4a2f1d;
                border-radius: 5px;
            }

            .rune-option:hover {
                background: rgba(139, 69, 19, 0.2);
                transform: scale(1.05);
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            }

            #message {
                margin-top: 15px;
                padding: 12px;
                border-radius: 5px;
                font-weight: bold;
            }

            .error { 
                background-color: rgba(169, 68, 66, 0.2);
                border: 2px solid #a94442;
                color: #a94442;
            }

            .success { 
                background-color: rgba(60, 118, 61, 0.2);
                border: 2px solid #3c763d;
                color: #3c763d;
            }

            #instruction {
                color: #4a2f1d;
                font-size: 1.1em;
                margin: 15px 0;
                padding: 10px;
                background: rgba(139, 69, 19, 0.1);
                border-radius: 5px;
            }
        </style>
    </head>
    <body>
        <div class="captcha-container">
            <h3>Verificação de Segurança Necessária</h3>
            <p>Para continuar jogando, complete esta verificação de segurança.</p>
            <p id="instruction"></p>
            <div class="runes-display" id="runesDisplay"></div>
            <div class="rune-options" id="runeOptions"></div>
            <div id="message"></div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                generateNewCaptcha();
            });

            function generateNewCaptcha() {
                fetch('captcha.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'action=generate'
                })
                .then(response => response.json())
                .then(data => {
                    const runesDisplay = document.getElementById('runesDisplay');
                    const instruction = document.getElementById('instruction');
                    runesDisplay.innerHTML = '';
                    
                    // Display runes
                    data.sequence.forEach(rune => {
                        const span = document.createElement('span');
                        span.innerHTML = rune;
                        runesDisplay.appendChild(span);
                    });
                    
                    // Set instruction
                    instruction.textContent = `Selecione a runa na posição ${data.position} ${data.left_to_right ? 'da esquerda para a direita' : 'da direita para a esquerda'}`;
                    
                    // Generate options
                    const runeOptions = document.getElementById('runeOptions');
                    runeOptions.innerHTML = '';
                    Object.entries(data.options).forEach(([id, name]) => {
                        const option = document.createElement('div');
                        option.className = 'rune-option';
                        option.onclick = () => validateAnswer(id);
                        option.textContent = name;
                        runeOptions.appendChild(option);
                    });
                    
                    // Clear any previous messages
                    const message = document.getElementById('message');
                    message.style.display = 'none';
                    message.className = '';
                })
                .catch(error => {
                    console.error('Error:', error);
                    const message = document.getElementById('message');
                    message.textContent = 'Erro ao gerar verificação. Por favor, atualize a página.';
                    message.className = 'error';
                    message.style.display = 'block';
                });
            }

            function validateAnswer(answer) {
                fetch('captcha.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: `action=validate&answer=${answer}`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erro na rede');
                    }
                    return response.json();
                })
                .then(data => {
                    const message = document.getElementById('message');
                    message.textContent = data.message;
                    message.className = data.success ? 'success' : 'error';
                    message.style.display = 'block';
                    
                    if (data.success) {
                        // Disable all rune options during the delay
                        const options = document.querySelectorAll('.rune-option');
                        options.forEach(option => {
                            option.style.pointerEvents = 'none';
                            option.style.opacity = '0.5';
                        });

                        // Add countdown text to message
                        let secondsLeft = 5;
                        message.textContent += ` Redirecionando em ${secondsLeft} segundos...`;
                        
                        const countdown = setInterval(() => {
                            secondsLeft--;
                            message.textContent = `${data.message} Redirecionando em ${secondsLeft} segundos...`;
                            
                            if (secondsLeft <= 0) {
                                clearInterval(countdown);
                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                }
                            }
                        }, 1000);
                    } else if (data.new_captcha) {
                        // Update the captcha display with the new sequence
                        const runesDisplay = document.getElementById('runesDisplay');
                        const instruction = document.getElementById('instruction');
                        runesDisplay.innerHTML = '';
                        
                        // Display new runes
                        data.new_captcha.sequence.forEach(rune => {
                            const span = document.createElement('span');
                            span.innerHTML = rune;
                            runesDisplay.appendChild(span);
                        });
                        
                        // Set new instruction
                        instruction.textContent = `Selecione a runa na posição ${data.new_captcha.position} ${data.new_captcha.left_to_right ? 'da esquerda para a direita' : 'da direita para a esquerda'}`;
                        
                        // Generate new options
                        const runeOptions = document.getElementById('runeOptions');
                        runeOptions.innerHTML = '';
                        Object.entries(data.new_captcha.options).forEach(([id, name]) => {
                            const option = document.createElement('div');
                            option.className = 'rune-option';
                            option.onclick = () => validateAnswer(id);
                            option.textContent = name;
                            runeOptions.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const message = document.getElementById('message');
                    message.textContent = 'Erro ao validar resposta. Por favor, atualize a página e tente novamente.';
                    message.className = 'error';
                    message.style.display = 'block';
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                window.handleCaptchaResponse = function(response) {
                    if (response.success) {
                        const message = document.getElementById('message');
                        message.textContent = response.message;
                        message.className = 'success';
                        message.style.display = 'block';
                        
                        // Disable all rune options during the delay
                        const options = document.querySelectorAll('.rune-option');
                        options.forEach(option => {
                            option.style.pointerEvents = 'none';
                            option.style.opacity = '0.5';
                        });

                        // Add countdown text to message
                        let secondsLeft = 5;
                        message.textContent += ` Redirecionando em ${secondsLeft} segundos...`;
                        
                        const countdown = setInterval(() => {
                            secondsLeft--;
                            message.textContent = `${response.message} Redirecionando em ${secondsLeft} segundos...`;
                            
                            if (secondsLeft <= 0) {
                                clearInterval(countdown);
                                window.location.href = 'home.php';
                            }
                        }, 1000);
                    } else {
                        // Handle error case
                        if (response.message) {
                            alert(response.message);
                        }
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    }
                };
            });
        </script>
    </body>
    </html>
    <?php
} catch (Exception $e) {
    error_log("Erro no captcha: " . $e->getMessage());
    header('Location: index.php');
    exit;
}
?>