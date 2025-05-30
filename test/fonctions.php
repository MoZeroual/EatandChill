<?php

function email($mail)
{
    echo $mail;
    if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui présentent des bogues.
    {
        $passage_ligne = "\r\n";
    }
    else
    {
        $passage_ligne = "\n";
    }
    //=====Déclaration des messages au format texte et au format HTML.
    $message_txt = "ça va ou quoi ? Est ce que ça bosse ?.";    
    $message_html = "<html><head></head><body><b>Salut dimitri</b>, voici un e-mail envoyé par <i>Samy</i>.</body></html>";
    //==========
    
    //=====Lecture et mise en forme de la pièce jointe.
    $fichier   = fopen("inc/img/1.jpg", "r");
    $attachement = fread($fichier, filesize("inc/img/1.jpg"));
    $attachement = chunk_split(base64_encode($attachement));
    fclose($fichier);
    //==========
    
    //=====Création de la boundary.
    $boundary = "-----=".md5(rand());
    $boundary_alt = "-----=".md5(rand());
    //==========
    
    //=====Définition du sujet.
    $sujet = "Doranco classe";
    //=========
    
    //=====Création du header de l'e-mail.
    $header = "From: \"SamyL\"<samyliani.tk>".$passage_ligne;
    $header.= "Reply-to: \"SamyL\" <samyliani.tk>".$passage_ligne;
    $header.= "MIME-Version: 1.0".$passage_ligne;
    $header.= "Content-Type: multipart/mixed;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
    //==========
    
    //=====Création du message.
    $message = $passage_ligne."--".$boundary.$passage_ligne;
    $message.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary_alt\"".$passage_ligne;
    $message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
    //=====Ajout du message au format texte.
    $message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
    $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    $message.= $passage_ligne.$message_txt.$passage_ligne;
    //==========
    
    $message.= $passage_ligne."--".$boundary_alt.$passage_ligne;
    
    //=====Ajout du message au format HTML.
    $message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
    $message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
    $message.= $passage_ligne.$message_html.$passage_ligne;
    //==========
    
    //=====On ferme la boundary alternative.
    $message.= $passage_ligne."--".$boundary_alt."--".$passage_ligne;
    //==========
    
    
    
    $message.= $passage_ligne."--".$boundary.$passage_ligne;
    
    //=====Ajout de la pièce jointe.
    $message.= "Content-Type: image/jpeg; name=\"1.jpg\"".$passage_ligne;
    $message.= "Content-Transfer-Encoding: base64".$passage_ligne;
    $message.= "Content-Disposition: attachment; filename=\"1.jpg\"".$passage_ligne;
    $message.= $passage_ligne.$attachement.$passage_ligne.$passage_ligne;
    $message.= $passage_ligne."--".$boundary."--".$passage_ligne; 
    //========== 
    //=====Envoi de l'e-mail.
    mail($mail,$sujet,$message,$header);
}
function sms(){
     
    require('smsenvoi.php');


    $smsenvoi=new smsenvoi();
    $smsenvoi->debug=true;

    //Passage d'un appel :
    $smsenvoi->sendCALL('+33603236869','Bonjour voici un test');



    //Obtention des crédits restants :
    $credits=$smsenvoi->checkCredits();

    //Les crédits call['PHONE'] correspondent aux crédits vers les lignes fixes
    //Les crédits call['MOBILEPHONE'] correspondent aux crédits vers les lignes mobiles
    //config

        
    
    $url        = 'https://api.allmysms.com/http/9.0/sendSms/';
    $login = 'samyliani';    //votre identifant allmysms
    $apiKey   = '529fb0766a8a90b';    //votre mot de passe allmysms
        
    $message    = 'Salut ça va';    //le message SMS, attention pas plus de 160 caractères
    $sender     = 'Samy liani';  //l'expediteur, attention pas plus de 11 caractères alphanumériques
    $msisdn     = '33603236869';    //numé©ro de téléphone du destinataire
    $smsData    = "<DATA>
    <MESSAGE><![CDATA[".$message."]]></MESSAGE>
    <TPOA>$sender</TPOA>
    <SMS>
        <MOBILEPHONE>$msisdn</MOBILEPHONE>
    </SMS>
    </DATA>";

    $fields = array(
    'login'    => urlencode($login),
    'apiKey'      => urlencode($apiKey),
    'smsData'       => urlencode($smsData),
    );

    $fieldsString = "";
    foreach($fields as $key=>$value) {
        $fieldsString .= $key.'='.$value.'&';
    }
    rtrim($fieldsString, '&');

    try {

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fieldsString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        echo $result;

        curl_close($ch);

    } catch (Exception $e) {
        echo 'Api allmysms injoignable ou trop longue a repondre ' . $e->getMessage();
    }
}

?>