# Symfony mailer driver for Google Workspace
Gmail APIによるsymfony mailerドライバーです。Laravelで使うことを想定しています。
Google Workspaceのドメイン全体の委任を受けたサービスアカウントが必要です。

## メリット
Gmail経由でメールが送信されるので、GoogleWorkspaceを導入した組織がメールマーケティングを行う上では、少し幸せになれます。
- メールの送信元のアイコン画像が表示される
- SPAM対策が楽
- メールボックスの送信履歴に残る

## デメリット
Gmail APIには送信制限があるので、注意してください。

## Laravelへの導入の仕方

### サービスアカウントの作成と設定

まず、下記の手順に従い、サービスアカウントを作成してください。
その際、Google Workspaceの権限付与で下記のスコープを追加してください。
`https://www.googleapis.com/auth/gmail.send`

※ Gmail APIの全てを許可する`https://mail.google.com/` だとこの実装ではダメです。

### プロジェクトへの導入手順

composerでインストールしてください。
```
composer install laravel-googleworkspace-mailer
```

config/mail.phpに設定を追加してください。
```
'google-workspace' => [
    'transport' => 'google-workspace',
    'credentials' => env('MAIL_GOOGLEWORKSPACE_CREDENTIALS', base_path('google-credentials.json')),
]
```

サービスアカウントの"鍵"で発行されるcredentials.jsonを、プロジェクトのrootにgoogle-credentials.jsonを配置してください。

本番環境ではセキュアな場所に配置することをおすすめします。
```
'google-workspace' => [
    'transport' => 'google-workspace',
    'credentials' => env('MAIL_GOOGLEWORKSPACE_CREDENTIALS', base_path('google-credentials.json')),
]
```

AppServiceProvider.phpのboot()で下記を呼び出してください。
```
Mail::extend('google-workspace', function (array $config = []) {
    return new GoogleWorkspaceTransport($config);
});
```

あとは通常通りのMail::mailer('google-workspace')->send(...)などで送信できます。