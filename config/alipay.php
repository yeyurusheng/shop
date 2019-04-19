<?php
$config = array (
    //应用ID,您的APPID。
    'app_id' => "2016092300576110",

    //商户私钥
    'merchant_private_key' => "MIIEpQIBAAKCAQEAr36/pzyBdV9AF11qksaduiMEAXhk2t1PNkSaCSL+y9WYrc/bKP7Re3XyU8te+CV6rZFiBHi0llhs12U1sI/KueHrKQiZ37tujm7ezD20GPFqirOk+nyGq0t3qDjAiXsMX13SHQPUBGgk0JJfjL4wxUnH9LF6iP/WgshizGxjQx36xNro5wzvFRM5ize0BFhWL1pgCqW6DE6ZI3rn8Ad5S3y729BTIIJFN0WtFl1Kp5veVoY8rM1SPUJjo9w7P0plzLwsL1zUgLQc9WQFV0+DE7L8RitgxPvEU6jnpBNoCQJVkW6q0EIzAZnQfkqjV5i2g2DQkrO05th/L8Z6Oldp4QIDAQABAoIBAQCgbra2I9IVgqYMthGfbIL0jt4ymeVZT8gCTGSkxaE4pmOVQdeQeqL2wrRtdhWztE+aLLX2cIJmjx+xKY0Tg+BIXgek04AX0DkZbLGw9ReXVduYQPIpK33RSoRw/LDG/f4pkJNsgvtnq+073xQKjjP3p6NsRnNGmtt3Y8kuKEf2KgZZ/RPfQEpMSC41Io2vBun85K976OOu36/pRyxMCHwldMvskLOldfAtFaDYe5idKN3yjtjaixGyoxqeA9Mtm37U1JCUne8LRiCFN1ywe+/NeRsu7Lp+WJB2LavMpejZ1+GBWax5nw0O8GWIQ5x9LqpBG1Ij4OU9EohD5ETj62yhAoGBANjTR/OHfNEhltQ5J4Sg5khYKyVdVt43AAUCoOkK0qjRzKrxGc88+6Xlw2mG2GGFXTtBwlOUa5uy9EPStPu0K1TABBw6kYzz8ooCgRZq0LtQA3DmYco6VfL7DbENZCQLZ9s0WKPQnGCGccjRn4hxziDEVWIQ1PUYIxqDZGa/PDNDAoGBAM8z11WmThFi4RbzB6lQa3yLZ8i9rin6R3CBSXp0cuQJflt/1e3MCPlYQJs3EUwo+PU1mFihRQyXADtdNaslmiRuKVt/JBKPbz53llHrd5D/F7ubXp82B9OtDCWfquaaOpz8eFBpH3PerOHL33zusyMyzNyvHbXeZlaYtU87l5ILAoGBALr/7YwhDAsp1vWNnYGLa1B1ijCdgbAA62lmzPRwOyMSXlwHuGS9iaOYt6IphDEHXlMe8cQ3u177RiPAdAqpZr2fJWxbLvDL7CAO7NoyoXcLGQykBS7uhPYcg8Bxdi6ID7hEOzZ9UoEFMtkpwVdiH46vBZygO6pueMgDDfJ3eCoVAoGBAL2eHJbmLZVdns3ItJ8u2F7OmLitVSBXSV9dAahNvwKO6ZLEpzsKOMAvEli5CVxgNoz4ciXR2AXQfkbVYxLvw2NJaRGxhYpytwQ8nlTznqUTvV1Tr0EWSg1d1LrtppGIXNFjfptfyIfaJO4yC6EiNM+mYrImk5LnJ1JiR7Jtv6THAoGAfveIlb7NysGTrmQBgTBkYMapD55dtHARP/kP96kr/3oDrd9+mmoysKgnx8/woD3jxRVc5ovwPs0NMIAzFBUsHYC6UWj/OHlyKz98QjeN/oNIZNG/MvYiswjK9AK8GN0kHJwUOac+t0jlAKJXW5wIeExLpeIJJ8JsITvhV21BrNs=",

    //异步通知地址
    'notify_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/notify_url.php",

    //同步跳转
    'return_url' => "http://外网可访问网关地址/alipay.trade.page.pay-PHP-UTF-8/return_url.php",

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type'=>"RSA2",

    //支付宝网关
    'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAoSlz5bKzetLyascaqtKTUcCiQoNiL6TE8Xw2SE9M/JwXS9OBzyRrch27B85KLH4+SSGwTkydAcktrWZK15Xm+yDXqy551vxKcbQwO+0cY283qu3/EH2TNz9FOY1XYJu6Or9UNj9oe2tz9IZxnG2a2GTMX0YTDP4ie3mQTlmuxyVvAWkbSHvMtIXT4dcpKdSeTjxm4TAel0V6mFDOzO/eChLVvWhrqlRrKw0rAMPz1UiBnw1rxmnUeaxLu3iXRwbrqf6g2u9rclHIqNT0V9246hx5btsckJSUYYGhzP7xIydKx7g0f1+S68uCyctGagybQHS9Mxnv8g05BHqIyD0kVQIDAQAB",
);
return $config;