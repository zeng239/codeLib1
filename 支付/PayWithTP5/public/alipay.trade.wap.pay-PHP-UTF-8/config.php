<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2017102709555932",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDAAPr14Kb0c/98SD4IieWUUG1gcb9LYbiv+GL+JfeAm8G+CP+wvHpJVe1qwQsl7eAeQRk+pAhD6sSN1UWRu9HU14qNNqU8qENDn4dRWpgZC6IZzY8eaAZtSmldNKiqxgpm3MWQ/vYdNvDYUhq9KQg6p9yBa+erf+ojE3n6/Uibrto6Mc0+doIPSlU+iFy+C9RRTc7Jo2wK/SCJbuPmTjkw6Wot0l/E9rCyL/wD8sOhyxMNF2bJ2Ih34zbP5Fn9pkTZ5fyVa7pHzQRpRKKnie+uQTNPrwVPkZphA43QVMbCDYMNoqx97ZOElo9XsUhAyCNtFoCbcCoFfxazUPUi+ffxAgMBAAECggEAGVPHxwlkXJi0metZq3ytORxG4VL/3ey9FRFZ1ZaiiGeDbhL3z5N9OhFIqS1f0BgJ2VPTBa1TncnpNaBtdeTlsALitu//afn0LYZsrjGaIKulLWwtNeCZAG+xaGca3LQVCS6ULSVEx+PIb3CdMFiqSjp8XuIiBEByXUAjdhVVL5vtiyq6tYNZgv4eN4ue6aKf1cxXi/UDzLhZP1grWjwOjUlDpfTKToA3evD3ymubp0oH86EUxQPvAj4AMrcd76zPxCsl21rCzxxMhKQu8X9lM4DuwKja1PdlF5lHlq1Z3FALf1Eli8aAo4QthXRwFlSri+GLRrCUFCbKsNXn7BpEAQKBgQDmNbRiF8AhjaehMYdMtWJMC9e1AH+celfETTRTf+2e05kG0zAFulvrnlIiz6oA1pdo+BrowiuJLQ8FAuV+Lt9Ya4jvZSSgPHkRb0CEHYAL8cvup29AiFNlOCXQ7YoOs2bJHZTopw0Y6pjIa+gNVk7n5DZPA8/9FQe/NnngwQ7HwQKBgQDVg4yN4SajH9YiTt385Yaihm0HclvQsrm8EgTEYzJaxr1sWYaJrebnc/m4xF3m7I4I2h2d63a/FlNd/bLiHxBhC7J7mUPtqK9+CeWwoIi6k+okIAsS0ABnlwN8+8HKKRGDvHMh8z6EE7Q/g5S2DS/EgXhO9gFIIa+cMx3zEXS8MQKBgQCLqelV2bLmqFoaoUHEeAa6vDSRy0ugU1kL7wrf3az7vsQIL/figb1ipRqPpA0vlQEm3d71d1eSUZPbJna0pxs5OYRKOMKPtCB/Q0+Q15Tnqjpe/5WvQQXXUskeh/5KDO0+9oNw4mg/xZQLg+u0q7gTV5DbPyTgFfItP4+Rm46sAQKBgQC1v8wmffDwzMnv3Gi0vQEVWg53V0524ZyfTktGjRYxFZnrIG4YxrKWdS+uq2EvkE/7kwfBBM3JLYz7pljeiQjdQyLEeZvMM1pnlK8z4gNhp0WZSLmEYxDlHTR38KpulvT0ybbLwI6HyWoW4r/1FFaEFJ2/wUMoDpy4/v5QiZxwEQKBgH0xa25121Q+G2forChEv7isqk6ev2Z+4IXG1DO2JFWfvu783plYG9d8RwwbIsK0dALcWaizqYZ3kbA0r4f1dLX5HxFjbYveeJjNb98KT+f7OpTq0XnhVdbjo+23xrpq813YvMeX/DtocqsUgyX4eSSvfF9nd01uccTSn9uFRi42",
		
		//异步通知地址
		'notify_url' => "http://demo1.weiyunstudio.com/PayInTP5/index.php/Index/pay/pay_notify",
		
		//同步跳转
		'return_url' => "http://demo1.weiyunstudio.com/PayInTP5/index.php/Index/pay/pay_success",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwAD69eCm9HP/fEg+CInllFBtYHG/S2G4r/hi/iX3gJvBvgj/sLx6SVXtasELJe3gHkEZPqQIQ+rEjdVFkbvR1NeKjTalPKhDQ5+HUVqYGQuiGc2PHmgGbUppXTSoqsYKZtzFkP72HTbw2FIavSkIOqfcgWvnq3/qIxN5+v1Im67aOjHNPnaCD0pVPohcvgvUUU3OyaNsCv0giW7j5k45MOlqLdJfxPawsi/8A/LDocsTDRdmydiId+M2z+RZ/aZE2eX8lWu6R80EaUSip4nvrkEzT68FT5GaYQON0FTGwg2DDaKsfe2ThJaPV7FIQMgjbRaAm3AqBX8Ws1D1Ivn38QIDAQAB",
		
	
);