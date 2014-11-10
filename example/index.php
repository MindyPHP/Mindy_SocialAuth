<?php

$socialAuth = new Mindy\SocialAuth\SocialAuth([
    'providers' => [
        'vk' => [
            'class' => 'Mindy\SocialAuth\Provider\Vk',
            'clientId' => '4624319',
            'clientSecret' => 'jGt3m9yFpp9KYeDQhUXS',
            'redirectUri' => 'http://localhost:8001/?provider=vk'
        ],
        'facebook' => [
            'class' => 'Mindy\SocialAuth\Provider\Facebook',
            'clientId' => '745714052131897',
            'clientSecret' => '78cba2fc7ecf931f6e0e27fa52437668',
            'redirectUri' => 'http://localhost:8001/?provider=facebook'
        ],
        'yandex' => [
            'class' => 'Mindy\SocialAuth\Provider\Yandex',
            'clientId' => '84bf2001108f4bc0a7bdd6b89cac4898',
            'clientSecret' => '4670f5e9130d414fbcb8d5bf2c07de7e',
            'redirectUri' => 'http://localhost:8001/?provider=yandex'
        ],
        'google' => [
            'class' => 'Mindy\SocialAuth\Provider\Google',
            'clientId' => '766032454073-9l4kirl2t6iiitrspf5au0pfhl3f9mgq.apps.googleusercontent.com',
            'clientSecret' => 'pA5DW8IlbtQ_56q9SCQuBcQB',
            'redirectUri' => 'http://localhost:8001/?provider=google'
        ],
    ]
]);

if ($socialAuth->process('google')) {
    // ...
}

// or

$provider = $socialAuth->getProvider('google');
if ($provider->process()) {
    var_dump($provider->getUserInfo());
    if (!is_null($provider->getSocialId())) {
        echo "Социальный ID пользователя: " . $provider->getSocialId() . '<br />';
    }

    if (!is_null($provider->getName())) {
        echo "Имя пользователя: " . $provider->getName() . '<br />';
    }

    if (!is_null($provider->getEmail())) {
        echo "Email пользователя: " . $provider->getEmail() . '<br />';
    }

    if (!is_null($provider->getSocialPage())) {
        echo "Ссылка на профиль пользователя: " . $provider->getSocialPage() . '<br />';
    }

    if (!is_null($provider->getSex())) {
        echo "Пол пользователя: " . $provider->getSex() . '<br />';
    }

    if (!is_null($provider->getBirthday())) {
        echo "День Рождения: " . $provider->getBirthday() . '<br />';
    }

// аватар пользователя
    if (!is_null($provider->getAvatar())) {
        echo '<img src="' . $provider->getAvatar() . '" />';
    }
}
