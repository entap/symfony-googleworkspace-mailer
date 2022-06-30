# Symfony mailer driver for Google Workspace
Provides Google Workspace Gmail integration for Symfony Mailer.

## How to use (Japanese) for Laravel

composerでインストール。
```
composer install laravel-googleworkspace-mailer
```

config/mail.phpに設定を追加する。
```
'google-workspace' => [
    'transport' => 'google-workspace',
    'credentials' => env('MAIL_GOOGLEWORKSPACE_CREDENTIALS', base_path('google-credentials.json')),
]
```

プロジェクトのrootにgoogle-credentials.jsonを配置する。本番環境ではちゃんとした場所に置いてください。
```
'google-workspace' => [
    'transport' => 'google-workspace',
    'credentials' => env('MAIL_GOOGLEWORKSPACE_CREDENTIALS', base_path('google-credentials.json')),
]
```

Google Workspaceのドメイン権限を譲渡しつつ権限は下記が必要。
GMAIL_SEND

AppServiceProvider.phpのboot()で下記を呼び出す。
```
Mail::extend('google-workspace', function (array $config = []) {
    return new GoogleWorkspaceTransport($config);
});
```