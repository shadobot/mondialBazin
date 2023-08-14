<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private $api_key = 'b5bcc66cf1012958609bbabbb28ee9ea';
    private $api_key_secret = '497ed642b1f250bb163384120bf95e0f';

    public function send($to_email,$to_name, $subject, $content){
        $mj = new Client($this -> api_key, $this -> api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "mohamed.keita@mgk-formation.fr",
                        'Name' => "Mondial Bazin"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 5001016,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}