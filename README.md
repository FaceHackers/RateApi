<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>



# 匯率轉換API

### Technology

- Laravel 8.83.1
- PHP 7.3.19
- MySQL
- JWT(JSON Web Token)

### Reference 

- https://tw.rter.info/howto_currencyapi.php
- https://www.backpackers.com.tw/guide/index.php/%E4%B8%96%E7%95%8C%E5%90%84%E5%9C%8B%E8%B2%A8%E5%B9%A3

### Installation

- https://www.postman.com/


### Example

#### 註冊使用者
<p align="center"><a href="https://upload.cc/i1/2022/03/09/MovyZg.png" target="_blank"><img src="https://upload.cc/i1/2022/03/09/MovyZg.png
" ></a></p>

#### 登入取得 JWT Token
<p align="center"><a href="https://upload.cc/i1/2022/03/09/nzevXr.png" target="_blank"><img src="https://upload.cc/i1/2022/03/09/nzevXr.png
" ></a></p>

#### 各國匯率轉換
<p align="center"><a href="https://upload.cc/i1/2022/03/09/LEPm5O.png" target="_blank"><img src="https://upload.cc/i1/2022/03/09/LEPm5O.png" ></a></p>

## API
### 註冊使用者參數

##### URL: {domain}/api/auth/register
##### method: POST

 Parameter | type |  Description |
| --- | --- |  --- |  --- |
| name | string | 姓名
| email | string | 信箱
| password | string | 密碼
| password_confirmation | string | 確認密碼

##### Response(JSON)
 Parameter | type |  Description |
| --- | --- |  --- |  --- |
| code | int | (1=成功, 0= 失敗)
| message | string | 訊息


### 登入取得 JWT Token

##### URL: {domain}/api/auth/login
##### method: POST
     
 Parameter | type |  Description |
| --- | --- |  --- |  --- |
| email | string | 信箱
| password | string | 密碼

##### Response(JSON)
 Parameter | type |  Description |
| --- | --- |  --- |  --- |
| code | int | (1=成功, 0= 失敗)
| access_token | string | JWT Token
| expires_in | int | JWT 有效時間(單位秒)
| message | string | 訊息

### 匯率轉換

##### URL: {domain}/api/rateApi
##### method: POST
##### HTTP Header ：Bearer JWT Token

 Parameter | type |  Description |
| --- | --- |  --- |  --- |
| from | string | 貨幣代碼(Ex: from=USD, to=TWD, 美金換算台幣)
| to | string | 貨幣代碼(Ex: from=TWD, to=USD, 台幣換算美金)
| money | int | 金額
