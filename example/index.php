<?php

$auth = new Mindy\SocialAuth\SocialAuth([
    'providers' => [
        'vk' => [
            'class' => 'Mindy\SocialAuth\Adapter\Vk',
            'clientId' => '4624319',
            'clientSecret' => 'jGt3m9yFpp9KYeDQhUXS',
            'redirectUri' => 'http://localhost:8001/?provider=vk'
        ],
        'facebook' => [
            'class' => 'Mindy\SocialAuth\Adapter\Facebook',
            'clientId' => '745714052131897',
            'clientSecret' => '78cba2fc7ecf931f6e0e27fa52437668',
            'redirectUri' => 'http://localhost:8001/?provider=facebook'
        ],
        'yandex' => [
            'class' => 'Mindy\SocialAuth\Adapter\Yandex',
            'clientId' => '84bf2001108f4bc0a7bdd6b89cac4898',
            'clientSecret' => '4670f5e9130d414fbcb8d5bf2c07de7e',
            'redirectUri' => 'http://localhost:8001/?provider=yandex'
        ],
        'google' => [
            'class' => 'Mindy\SocialAuth\Adapter\Google',
            'clientId' => '766032454073-9l4kirl2t6iiitrspf5au0pfhl3f9mgq.apps.googleusercontent.com',
            'clientSecret' => 'pA5DW8IlbtQ_56q9SCQuBcQB',
            'redirectUri' => 'http://localhost:8001/?provider=google'
        ],
    ]
]);

if (!isset($_GET['code'])) {
    header("Location: " . $auth->getProvider('google')->getAuthUrl());
} else {
    try {
        $auth->authenticate();
    } catch(\Exception $e) {
        header("Location: /");
    }

    $auther = $auth->getProvider();
    var_dump($auther->getInfo());
    if (!is_null($auther->socialId))
        echo "Социальный ID пользователя: " . $auther->socialId . '<br />';

    if (!is_null($auther->name))
        echo "Имя пользователя: " . $auther->name . '<br />';

    if (!is_null($auther->email))
        echo "Email пользователя: " . $auther->email . '<br />';

    if (!is_null($auther->socialPage))
        echo "Ссылка на профиль пользователя: " . $auther->socialPage . '<br />';

    if (!is_null($auther->sex))
        echo "Пол пользователя: " . $auther->sex . '<br />';

    if (!is_null($auther->birthday))
        echo "День Рождения: " . $auther->birthday . '<br />';

    // аватар пользователя
    if (!is_null($auther->avatar))
        echo '<img src="' . $auther->avatar . '" />';
    echo "<br />";
}