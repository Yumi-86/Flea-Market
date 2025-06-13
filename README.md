# アプリケーション名
### coachtechフリマ

## 環境構築

#### 1. Docker ビルド

```bash
git clone リンク
docker-compose up -d --build
```

\*MySQL は、OS によって起動しない場合があるのでそれぞれの PC に合わせて docker-compsoe.yml ファイルを編集してください。

#### 2. Laravel 環境構築

・composer のインストール

```bash
docker-compose exec php bash
composer install
```

#### 3. 日本語ファイルの導入

このプロジェクトでは Laravel のバリデーションメッセージ等を日本語化するために [laravel-lang/lang](https://github.com/Laravel-Lang/lang) を使用しています。<br>

セットアップ手順:

```bash
composer require laravel-lang/lang:~7.0 --dev
cp -r ./vendor/laravel-lang/lang/src/ja ./resources/lang/
```

Laravel の設定ファイル config/app.php の以下の項目が ja になっていることを確認してください。

```php
'local' => 'ja',
```

#### 4. .env.example をコピーし.env ファイルを作成、環境変数の変更。

#### 5. アプリケーションキーの設定

```bash
php artisan key:generate

```

#### 6. マイグレーション、シーディングの実行

```bash
php artisan migrate
php artisan db:seed

```
#### 7. シンボリックリンクの実行
```bash
php artisan storage:link
```

"The stream or file could not be opened"エラーが発生した場合
src ディレクトリにある storage ディレクトリに権限を設定

```
chmod -R 777 storage
```

## 使用技術

フレームワーク：Laravel 8.7<br>
言語：PHP 7.4.9<br>
データベース：MySQL 8.0<br>
Web サーバー：Nginx 1.21.1<br>
管理ツール：phpMyAdmin<br>
実行環境：Docker（docker-compose v3.8）<br>

## ER 図

![ER図](ER.drawio.png)

## URL

- phpMyAdmin : http://localhost:8080/
- 商品一覧画面（トップページ）: http://localhost/
- 会員登録画面 : http://localhost/register
- ログイン画面 : http://localhost/login
- マイページ（プロフィール） : http://localhost/profile
- 商品出品画面 : http://localhost/items/sell
