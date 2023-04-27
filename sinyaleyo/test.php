<?php
function checkWebsiteSecurity() {
    // Check if URL parameter was sent
    if (!isset($_POST['url'])) {
        echo "<p>Aucun paramètre d'URL n'a été envoyé</p>";
        return;
    }

    // Get the URL parameter and filter it for safety
    $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);

    // Verify that the URL is valid and safe
    if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
        echo "<p>L'URL est invalide ou non sécurisée</p>";
        return;
    }
    // Check if domain is reliable
    $domain = parse_url($url, PHP_URL_HOST);
    $ip = gethostbyname($domain);

    if ($ip === $domain) {
        echo "<p>Le domaine n'est pas fiable</p>";
    } else {
        echo "<p>Le domaine est fiable</p>";
    }

    // Check if link is secure
    $parsedUrl = parse_url($url);

    if ($parsedUrl['scheme'] === 'https') {
        echo "<p>Le lien est sécurisé</p>";
    } else {
        echo "<p>Le lien n'est pas sécurisé</p>";
    }

    // Initialize a cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

    // Execute the cURL request
    $response = curl_exec($ch);

    // Get the HTTP status code
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Check whether the site uses SSL/TLS
    if(curl_errno($ch) == 0) {
        $sslInfo = curl_getinfo($ch, CURLINFO_SSL_VERIFYRESULT);
        if($sslInfo == 0) {
            echo "<p>Le site utilise le chiffrement SSL/TLS</p>";
        } else {
            echo "<p>Le site n'utilise pas le chiffrement SSL/TLS</p>";
        }
    }

    // Check whether the SSL/TLS certificate is valid
    if($httpCode == 200) {
        $certInfo = openssl_x509_parse(curl_exec($ch));
        if($certInfo !== false) {
            $validFrom = date('Y-m-d H:i:s', $certInfo['validFrom_time_t']);
            $validTo = date('Y-m-d H:i:s', $certInfo['validTo_time_t']);
            if($validTo > date('Y-m-d H:i:s') && $validFrom < date('Y-m-d H:i:s')) {
                echo "<p>Le certificat SSL/TLS est valide</p>";
            } else {
                echo "<p>Le certificat SSL/TLS n'est pas valide</p>";
            }
        } else {
            echo "<p>Impossible de vérifier le certificat SSL/TLS</p>";
        }
    }

    // Check the domain registration
    $whoisServer = "whois.iana.org";
    $domainInfo = '';
    $fp = fsockopen($whoisServer, 43, $errno, $errstr, 10);
    if($fp) {
        fputs($fp, $domain."\r\n");
        while(!feof($fp)) {
            $domainInfo .= fgets($fp, 128);
        }
        fclose($fp);
        preg_match('/Registrar WHOIS Server: (.*)/', $domainInfo, $matches);
        if(isset($matches[1])) {
            $whoisServer = $matches[1];
        }
        $fp = fsockopen($whoisServer, 43, $errno, $errstr, 10);
        if($fp) {
            fputs($fp, $domain."\r\n");
            while(!feof($fp)) {
                $domainInfo .= fgets($fp, 128);
            }
            fclose($fp);
            preg_match('/Creation Date: (.*)/', $domainInfo, $matches);
            $creationDate = isset($matches[1]) ? $matches[1] : 'inconnue';
            preg_match('/Registry Expiry Date: (.*)/', $domainInfo, $matches);
            $expiryDate = isset($matches[1]) ? $matches[1] : 'inconnue';
            echo "<p>Le domaine a été enregistré le $creationDate et expire le $expiryDate.</p>";
        } else {
            echo "<p>Impossible de contacter le serveur WHOIS pour obtenir les informations de domaine.</p>";
        }
    } else {
        echo "<p>Impossible de contacter le serveur WHOIS pour obtenir les informations de domaine.</p>";
    }

    // Check whether the site is reachable
    $dns = parse_url($url, PHP_URL_HOST);
    $ip = gethostbyname($dns);
    if ($ip === $dns) {
        echo "<p>Le site n'est pas accessible</p>";
    } else {
        echo "<p>Le site est accessible</p>";
    }

    // Close the cURL session
    curl_close($ch);
}
echo checkWebsiteSecurity();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Vérification de sécurité de site web</title>
</head>
<body>
    <h1>Vérification de sécurité de site web</h1>
    <form method="post">
        <label for="url">Entrez l'URL à vérifier :</label>
        <input type="text" id="url" name="url" required>
        <button type="submit">Vérifier</button>
    </form>
    <br>
</body>
</html>
