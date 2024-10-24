<?php
declare(strict_types=1);

function remoteFileExists($url)
{
    $curl = curl_init($url);

    //don't fetch the actual page, you only want to check the connection is ok
    curl_setopt($curl, CURLOPT_NOBODY, true);

    //do request
    $result = curl_exec($curl);

    $ret = false;

    //if request did not fail
    if ($result !== false) {
        //if request was ok, check response code
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($statusCode == 200) {
            $ret = true;
        }
    }

    curl_close($curl);

    return $ret;
}

// Função SmileEmoticons
function FunSmile($text, $smile = '0')
{
    $smilefun = [';D' => 1, ':D' => 1, '(b)' => 2, ':O' => 3, ';o' => 4, ';O' => 4, ':(' => 5, ';(' => 5, ':@' => 6, ';@' => 6, ':)' => 8, ';d' => 9, ':d' => 9, ':megusta:' => 'megusta', ':omg:' => 'omg', ':trollface:' => 'trollface', ':NAAO:' => 'NAAAO', ':chacc:' => 'challengeaccepted', ':cry:' => 'cryy', ';)' => 10];
    if ($smile == 1) {
        return $text;
    }

    // Altera os caracteres por imagens
    foreach ($smilefun as $search => $replace)
        $text = str_replace($search, '<img src="static/images/smile/' . $replace . '.gif" />', $text);

    return $text;
}


// Class BBCODE
class bbcode
{
    public function parse($text, $smile = '0')
    {
        // Lista de função BBCODE  

        $print = '';
        while (stripos($text, '[quote]') !== false && stripos($text, '[/quote]') !== false) {
            $quote = substr($text, stripos($text, '[quote]') + 7, stripos($text, '[/quote]') - stripos($text, '[quote]') - 7);
            $text = str_ireplace('[quote]' . $quote . '[/quote]', '<blockquote>' . $quote . '</blockquote>', $text);
        }
        

        // BBCODE "URL=" -> VERSAO ANTIGA
        $text = preg_replace("/\[url=(.*)\](.*)\[\/url\]/Usi", "<a href=\"\\1\" target=\"_blank\" border=\"0px\">\\2</a>", $text);

        // BBCODE "URL"
        while (stripos($text, '[url]') !== false && stripos($text, '[/url]') !== false) {
            $url = substr($text, stripos($text, '[url]') + 5, stripos($text, '[/url]') - stripos($text, '[url]') - 5);
            $text = str_ireplace('[url]' . $url . '[/url]', '<a href="' . $url . '" target="_blank">' . $url . '</a>', $text);
        }

        //BBCIDE "IMG INPUT"
        while (stripos($text, '[img]') !== false && stripos($text, '[/img]') !== false) {
            $img = substr($text, stripos($text, '[img]') + 5, stripos($text, '[/img]') - stripos($text, '[img]') - 5);

            $exists = remoteFileExists($img);
            if ($exists) {
                $text = str_ireplace('[img]' . $img . '[/img]', '<img style="max-width:460px; width: expression(this.width > 460 ? 460: true);" src="static/' . $img . '">', $text);
            } else {
                $text = str_ireplace('[img]' . $img . '[/img]', '[Imagem Invlida]', $text);
            }
        }

        //BBCODE "NEGRITO [B]"
        while (stripos($text, '[b]') !== false && stripos($text, '[/b]') !== false) {
            $a = substr($text, stripos($text, '[b]') + 3, stripos($text, '[/b]') - stripos($text, '[b]') - 3);
            $text = str_ireplace('[b]' . $a . '[/b]', '<b>' . $a . '</b>', $text);
        }

        //BBCODE "ITALIC [I]"
        while (stripos($text, '[i]') !== false && stripos($text, '[/i]') !== false) {
            $b = substr($text, stripos($text, '[i]') + 3, stripos($text, '[/i]') - stripos($text, '[i]') - 3);
            $text = str_ireplace('[i]' . $b . '[/i]', '<i>' . $b . '</i>', $text);
        }

        //BBCODE "DESSA PORRA DE [U] QUE EU NÃO SEI QUAL É NOME --> UNDERLINE]
        while (stripos($text, '[u]') !== false && stripos($text, '[/u]') !== false) {
            $c = substr($text, stripos($text, '[u]') + 3, stripos($text, '[/u]') - stripos($text, '[u]') - 3);
            $text = str_ireplace('[u]' . $c . '[/u]', '<u>' . $c . '</u>', $text);
        }

        //BBCODE "SMALL"
        while (stripos($text, '[small]') !== false && stripos($text, '[/small]') !== false) {
            $d = substr($text, stripos($text, '[small]') + 7, stripos($text, '[/small]') - stripos($text, '[small]') - 7);
            $text = str_ireplace('[small]' . $d . '[/small]', '<font size=1px>' . $d . '</font>', $text);
        }

        //BBCODE "BIG"
        while (stripos($text, '[big]') !== false && stripos($text, '[/big]') !== false) {
            $h = substr($text, stripos($text, '[big]') + 5, stripos($text, '[/big]') - stripos($text, '[big]') - 5);
            $text = str_ireplace('[big]' . $h . '[/big]', '<font size=5px>' . $h . '</font>', $text);
        }

        //BBCODE "CENTER"
        while (stripos($text, '[center]') !== false && stripos($text, '[/center]') !== false) {
            $h = substr($text, stripos($text, '[center]') + 8, stripos($text, '[/center]') - stripos($text, '[center]') - 8);
            $text = str_ireplace('[center]' . $h . '[/center]', '<center>' . $h . '</center>', $text);
        }

        //BBCODE "LEFT"
        while (stripos($text, '[left]') !== false && stripos($text, '[/left]') !== false) {
            $h = substr($text, stripos($text, '[left]') + 6, stripos($text, '[/left]') - stripos($text, '[left]') - 6);
            $text = str_ireplace('[left]' . $h . '[/left]', '<div align=left>' . $h . '</div>', $text);
        }

        //BBCODE "RIGHT"
        while (stripos($text, '[right]') !== false && stripos($text, '[/right]') !== false) {
            $h = substr($text, stripos($text, '[right]') + 7, stripos($text, '[/right]') - stripos($text, '[right]') - 7);
            $text = str_ireplace('[right]' . $h . '[/right]', '<div align=right>' . $h . '</div>', $text);
        }

        //BBCODE "LIST"
        while (stripos($text, '[list]') !== false && stripos($text, '[/list]') !== false) {
            $e = substr($text, stripos($text, '[list]') + 6, stripos($text, '[/list]') - stripos($text, '[list]') - 6);

            foreach (explode("<br />", $e) as $line) {
                if (strlen($line) > 2) { //gambiarra, parece que tem um /n escondido que tem q ignorar.
                    $print = $print . "<li>" . $line . "</li>";
                }
            }

            $text = str_ireplace('[list]' . $e . '[/list]', '<ul>' . $print . '</ul>', $text);
            $print = "";
        }

        //BBCODE "ORDER"
        while (stripos($text, '[order]') !== false && stripos($text, '[/order]') !== false) {
            $f = substr($text, stripos($text, '[order]') + 7, stripos($text, '[/order]') - stripos($text, '[order]') - 7);

            foreach (explode("<br />", $f) as $line) {
                if (strlen($line) > 2) { //gambiarra, parece que tem um /n escondido que tem q ignorar.
                    $print = $print . "<li>" . $line . "</li>";
                }
            }

            $text = str_ireplace('[order]' . $f . '[/order]', '<ol>' . $print . '</ol>', $text);
            $print = "";
        }

        // BBCODE "COLOR=" -> VERSAO ANTIGA
        $text = preg_replace("/\[color=(\#[0-9A-F]{6}|[a-z]+)\](.*)\[\/color\]/Usi", "<span style=\"color:\\1\">\\2</span>", $text);

        /* //BBCODE "[COLOR] - NECESSITA ALTERAÇÃO"
            while(stripos($text, '[color=]') !== false && stripos($text, '[/color]') !== false )
            {
                $i = substr($text, stripos($text, '[color]')+7, stripos($text, '[/color]') - stripos($text, '[color]') - 7);
                $text = preg_replace("/\[color=(\#[0-9A-F]{6}|[a-z]+)\](.*)\[\/color\]/Usi", "<span style=\"color:\\1\">".$i."</span>", $text);
            } */

        //BBCODE "[S]"
        while (stripos($text, '[s]') !== false && stripos($text, '[/s]') !== false) {
            $j = substr($text, stripos($text, '[s]') + 3, stripos($text, '[/s]') - stripos($text, '[s]') - 3);
            $text = str_ireplace('[s]' . $j . '[/s]', '<s>' . $j . '</s>', $text);
        }

        //BBCODE "YOUTUBE"
        while (stripos($text, '[youtube]') !== false && stripos($text, '[/youtube]') !== false) {
            $d = substr($text, stripos($text, '[youtube]') + 9, stripos($text, '[/youtube]') - stripos($text, '[youtube]') - 9);
            $text = str_ireplace('[youtube]' . $d . '[/youtube]', '<iframe width="420" height="315" src="static/http://www.youtube.com/embed/' . $d . '" frameborder="0" allowfullscreen></iframe>', $text);
        }

        //YOUTUBE OLD
        $text = str_replace("\\[youtube]([^\\[]*)\\[/youtube\\]", "<object width=\"425\" height=\"344\"><param name=\"movie\" value=\"http://www.youtube.com/v/\\1&hl=pt-br&fs=1&\"></param><param name=\"allowFullScreen\" value=\"true\"></param><param name=\"allowscriptaccess\" value=\"always\"></param><embed src=\"static/http://www.youtube.com/v/\\1&hl=pt-br&fs=1&\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"425\" height=\"344\"></embed></object>", $text);

        //BBCODE "YOUTUBE INPUT"
        /* while(stripos($text, '[youtube]') !== false && stripos($text, '[/youtube]') !== false )
            {
             $j = substr($text, stripos($text, '[youtube]')+9, stripos($text, '[/youtube]') - stripos($text, '[youtube]') - 9);
               if(preg_match("#http://(.*)\.youtube\.com/watch\?v=(.*)(&(.*))?#", $j, $matches))
                $text = str_ireplace('[youtube]'.$j.'[/youtube]', '<object width="425" height="344">
                       <param name="movie" value="http://www.youtube.com/v/'.$matches[2].'&hl=pt-br&fs=1"></param>
                       <param name="allowFullScreen" value="true"></param>
                       <param name="allowscriptaccess" value="always"></param>
                       <embed src="static/http://www.youtube.com/v/'.$matches[2].'&hl=pt-br&fs=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed>
                    </object>', $text);
           } */

        //RETORNANDO TEXTO COM SMILES
        return FunSmile($text, $smile);
    }
}
