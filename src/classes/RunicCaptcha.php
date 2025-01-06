<?php

declare(strict_types=1);

class RunicCaptcha {
    private const RUNES = [
        1 => ['emoji' => '&#x1F525;', 'name' => 'Fogo'],    // Fire 🔥
        2 => ['emoji' => '&#x1F300;', 'name' => 'Vento'],   // Wind 🌀
        3 => ['emoji' => '&#x1F4A7;', 'name' => 'Água'],    // Water 💧
        4 => ['emoji' => '&#x26A1;',  'name' => 'Raio'],    // Lightning ⚡
        5 => ['emoji' => '&#x2744;',  'name' => 'Gelo']     // Ice ❄️
    ];
    
    private const SEQUENCE_LENGTH = 4;
    private const CAPTCHA_INTERVAL = 1800; // 30 minutes in seconds
    private const TEMP_BAN_DURATION = 1800; // 30 minutes in seconds
    private const MAX_ATTEMPTS = 3;
    private const REWARD_GOLD = 1000;
    
    /**
     * Checks if user needs to solve a captcha and returns ban info if banned
     * @return array Status and ban information if applicable
     */
    public function needsCaptcha(): array {
        global $db;
        
        if (!isset($_SESSION['Login']) || !isset($_SESSION['Login']['account_id'])) {
            return ['needs_captcha' => false];
        }
        
        try {
            $account_id = (int)$_SESSION['Login']['account_id'];
            
            // Check if user is temporarily banned
            $banQuery = "SELECT ban_until FROM account_bans WHERE account_id = ?";
            $banResult = $db->execute($banQuery, [$account_id]);
            if ($banResult === false) {
                error_log("Database error in needsCaptcha (ban check): " . $db->ErrorMsg());
                return ['needs_captcha' => true];
            }
            
            if ($banResult->recordcount() > 0) {
                $banData = $banResult->fetchrow();
                $banUntil = (int)$banData['ban_until'];
                if ($banUntil > time()) {
                    $timeLeft = $banUntil - time();
                    if ($timeLeft < 60) {
                        $timeMsg = $timeLeft . " segundos";
                    } elseif ($timeLeft < 3600) {
                        $timeMsg = ceil($timeLeft / 60) . " minutos";
                    } else {
                        $timeMsg = ceil($timeLeft / 3600) . " horas";
                    }
                    return [
                        'needs_captcha' => true,
                        'is_banned' => true,
                        'time_left' => $timeMsg
                    ];
                }
            }
            
            // Check last captcha time
            $timeQuery = "SELECT last_captcha_time FROM accounts WHERE id = ?";
            $result = $db->execute($timeQuery, [$account_id]);
            if ($result === false) {
                error_log("Database error in needsCaptcha (time check): " . $db->ErrorMsg());
                return ['needs_captcha' => true];
            }
            
            if ($result->recordcount() > 0) {
                $userData = $result->fetchrow();
                $lastCaptchaTime = (int)$userData['last_captcha_time'];
                
                if (!$lastCaptchaTime) {
                    return ['needs_captcha' => true];
                }
                
                $timeSinceLastCaptcha = time() - $lastCaptchaTime;
                return ['needs_captcha' => $timeSinceLastCaptcha >= self::CAPTCHA_INTERVAL];
            }
            
            return ['needs_captcha' => true];
            
        } catch (Exception $e) {
            error_log("Error in needsCaptcha: " . $e->getMessage());
            return ['needs_captcha' => true];
        }
    }
    
    /**
     * Generates a new captcha sequence and stores it in the session
     * @return array Array containing the sequence info and position to guess
     */
    public function generateCaptcha(): array {
        if (!isset($_SESSION['Login']) || !isset($_SESSION['Login']['account_id'])) {
            return ['error' => 'Não logado'];
        }
        
        // Initialize or reset attempts counter
        if (!isset($_SESSION['captcha_attempts'])) {
            $_SESSION['captcha_attempts'] = 0;
        }
        
        $sequence = [];
        $availableRunes = array_keys(self::RUNES);
        
        // Generate random sequence
        for ($i = 0; $i < self::SEQUENCE_LENGTH; $i++) {
            $runeIndex = array_rand($availableRunes);
            $sequence[] = $availableRunes[$runeIndex];
        }
        
        // Randomly decide direction (left-to-right or right-to-left)
        $isLeftToRight = (random_int(0, 1) === 1);
        
        // Select random position to guess (1-based)
        $positionToGuess = random_int(1, self::SEQUENCE_LENGTH);
        
        // Store in session
        $_SESSION['runic_captcha'] = [
            'sequence' => $sequence,
            'position' => $positionToGuess,
            'left_to_right' => $isLeftToRight,
            'correct_rune' => $sequence[$isLeftToRight ? $positionToGuess - 1 : self::SEQUENCE_LENGTH - $positionToGuess],
            'generated_time' => time()
        ];
        
        return [
            'sequence' => array_map(fn($id) => html_entity_decode(self::RUNES[$id]['emoji'], ENT_HTML5, 'UTF-8'), $sequence),
            'position' => $positionToGuess,
            'left_to_right' => $isLeftToRight,
            'options' => array_combine(
                array_keys(self::RUNES),
                array_map(fn($rune) => $rune['name'], self::RUNES)
            )
        ];
    }
    
    /**
     * Validates the user's answer and handles rewards/punishments
     * @param int $answer The rune number chosen by the user
     * @return array Response containing success status and message
     */
    public function validateAnswer(int $answer): array {
        global $db;
        
        if (!isset($_SESSION['Login']) || !isset($_SESSION['Login']['account_id'])) {
            return ['success' => false, 'message' => 'Sessão inválida'];
        }
        
        if (!isset($_SESSION['runic_captcha'])) {
            return ['success' => false, 'message' => 'Sessão inválida'];
        }
        
        try {
            $account_id = (int)$_SESSION['Login']['account_id'];
            $correct = $_SESSION['runic_captcha']['correct_rune'] === $answer;
            
            if ($correct) {
                // Reset attempts counter
                $_SESSION['captcha_attempts'] = 0;
                
                try {
                    $db->startTrans();
                    
                    // Update last captcha time in accounts table
                    $updateAccountQuery = "UPDATE accounts SET 
                        last_captcha_time = ?
                        WHERE id = ?";
                    $updateAccountResult = $db->execute($updateAccountQuery, [time(), $account_id]);
                    if (!$updateAccountResult) {
                        $db->rollbackTrans();
                        error_log("Database error in validateAnswer (update account): " . $db->ErrorMsg());
                        return [
                            'success' => false,
                            'message' => 'Erro ao processar a recompensa. Por favor, tente novamente.'
                        ];
                    }

                    // Update gold in players table
                    $updateGoldQuery = "UPDATE players SET 
                        gold = gold + ?
                        WHERE acc_id = ?";
                    $updateGoldResult = $db->execute($updateGoldQuery, [self::REWARD_GOLD, $account_id]);
                    if (!$updateGoldResult) {
                        $db->rollbackTrans();
                        error_log("Database error in validateAnswer (update gold): " . $db->ErrorMsg());
                        return [
                            'success' => false,
                            'message' => 'Erro ao processar a recompensa. Por favor, tente novamente.'
                        ];
                    }
                    
                    // Clear any existing bans
                    $clearBanQuery = "DELETE FROM account_bans WHERE account_id = ?";
                    $clearResult = $db->execute($clearBanQuery, [$account_id]);
                    if (!$clearResult) {
                        $db->rollbackTrans();
                        error_log("Database error in validateAnswer (clear ban): " . $db->ErrorMsg());
                        return [
                            'success' => false,
                            'message' => 'Erro ao processar a recompensa. Por favor, tente novamente.'
                        ];
                    }
                    
                    $db->completeTrans();
                    
                    // Clear the captcha from session
                    unset($_SESSION['runic_captcha']);
                    
                    return [
                        'success' => true,
                        'message' => "Correto! Você foi recompensado com " . self::REWARD_GOLD . " de ouro!",
                        'redirect' => 'home.php'
                    ];
                } catch (Exception $e) {
                    $db->rollbackTrans();
                    error_log("Error in validateAnswer (correct): " . $e->getMessage());
                    return [
                        'success' => false,
                        'message' => 'Erro ao processar a recompensa. Por favor, tente novamente.'
                    ];
                }
            } else {
                // Increment attempts counter
                $_SESSION['captcha_attempts'] = ($_SESSION['captcha_attempts'] ?? 0) + 1;
                
                // If max attempts reached, ban the user
                if ($_SESSION['captcha_attempts'] >= self::MAX_ATTEMPTS) {
                    try {
                        $banQuery = "INSERT INTO account_bans (account_id, ban_until, reason) 
                                   VALUES (?, NOW() + INTERVAL ? SECOND, 'Falha na verificação de segurança')
                                   ON DUPLICATE KEY UPDATE ban_until = NOW() + INTERVAL ? SECOND";
                        $banResult = $db->execute($banQuery, [$account_id, self::TEMP_BAN_DURATION, self::TEMP_BAN_DURATION]);
                        if (!$banResult) {
                            error_log("Database error in validateAnswer (ban): " . $db->ErrorMsg());
                            return [
                                'success' => false,
                                'message' => 'Erro ao processar o bloqueio. Por favor, tente novamente.'
                            ];
                        }
                        
                        // Reset attempts counter
                        $_SESSION['captcha_attempts'] = 0;
                        
                        return [
                            'success' => false,
                            'message' => "Você excedeu o número máximo de tentativas. Sua conta foi temporariamente bloqueada.",
                            'redirect' => 'captcha.php'
                        ];
                    } catch (Exception $e) {
                        error_log("Error in validateAnswer (ban): " . $e->getMessage());
                        return [
                            'success' => false,
                            'message' => 'Erro ao processar o bloqueio. Por favor, tente novamente.'
                        ];
                    }
                }
                
                // Generate new captcha for next attempt
                $newCaptcha = $this->generateCaptcha();
                
                return [
                    'success' => false,
                    'message' => "Incorreto! Tentativa " . $_SESSION['captcha_attempts'] . " de " . self::MAX_ATTEMPTS,
                    'new_captcha' => $newCaptcha
                ];
            }
        } catch (Exception $e) {
            error_log("Error in validateAnswer: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro ao validar resposta. Por favor, tente novamente.'
            ];
        }
    }
} 