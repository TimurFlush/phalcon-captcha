# Phalcon-Captcha
Phalcon-Captcha представляет собой удобную библиотеку для реализации капчи на Вашем сайте. 
Библиотека поддерживает как привычные изображения, так и Google Recaptcha 2.

## Использование
После установки через Composer, пропишите в Вашем роутере следующее:
```
$router = new \Phalcon\Mvc\Router(false);
$router->mount(new \TimurFlush\PhalconCaptcha\Routes\Captcha());
```
Теперь, по адресу **http://your_site.com/captcha**, будет находиться картинка с капчой.

Затем, создайте в контейнере зависимостей **shared сервис** с названием **captcha**, который должен будет возвращать **Image** либо **Recaptcha** адаптер, в противном случае будет вызвана ошибка.
```
$di->setShared('captcha', function(){
    /*
    //Создаём Recaptcha адаптер
    $adapter = new \TimurFlush\PhalconCaptcha\Adapter\Recaptcha([
        'publicKey' => 'your/public/key',
        'privateKey => 'your/private/key',
    ]);
    return $adapter;
    */
    
    //Создаём Image адаптер
    $adapter = new \TimurFlush\PhalconCaptcha\Adapter\Image();
    
    //ниже настройки по-умолчанию
    
    /* Установить длину картинки. (опционально) */
    $adapter->setWidth(150);
    
    /* Установить высоту картинки. (опционально) */
    $adapter->setHeight(40);
    
    /* Установить длину капчи 4 символа. (опционально) */
    $adapter->setLength(4);
    
    /* Установить размер шрифта 15 символов. (опционально) */
    $adapter->setFontSize(15);
    
    /* Установить RGBA цвет для шрифта. (опционально) */
    $adapter->setFontColor(0, 0, 0, 127); 
    
    /* Установить RGBA цвет для фона. (опционально) */
    $adapter->setBackgroundColor(0, 0, 0, 127);
    
    /* Разрешить цифры в картинке. (опционально) */
    $adapter->setAllowNumbers(true);
    
    /* Запретить буквы в картинке. (опционально) */
    $adapter->setAllowLetters(false);
    
    /* Запрещаем рандомный наклон букв. (опционально) */
    $adapter->setRandomAngle(false);
    
    return $adapter;
});
```
После регистрации адаптера в контейнере, добавьте в свою форму следующий элемент:
```
<?php

class Form extends \Phalcon\Forms\Form
{
    public function initialize()
    {
        $captcha = new \TimurFlush\PhalconCaptcha\CaptchaElement('captcha');
        $captcha->addValidator(
            new \TimurFlush\PhalconCaptcha\CaptchaValidator(
                'message' => 'Неверная капча.',
                //cancelOnFail прописан уже за вас.
            )
        );
    }
}

```
В контроллере проверка должна идти по-стандарту через метод формы isValid()
```
<?php

class Form extends \Phalcon\Mvc\Controller
{
    public function loginAction()
    {
        $form = new Form();
        if ($this->request->isPost()){
            do{
                if (!$form->isValid($this->request->getPost()){
                    foreach($form->getMessages() as $messages)
                        echo $message->getMessage(); //вывод ошибок из формы если они есть
                    break;
                }
            }while(false);
            
            $this->view->form = $form;
            echo $this->view->render('login.html');
        }
    }
}
```
И уже в шаблоне, Вы определяете логику отображения.
```
<?php if ( $this->captcha->getAdapterName() === 'Image' ): ?>
    <!-- Вывод изображения с кастомизацией -->
    <?php echo $this->captcha->getImage([
        'width' => 180, //ширина изображения в пикселях (опционально)
        'height' => 50, //высота изображения в пикселях (опционально)
        'font_size' => 15, //размер шрифта (опционально)
        'font' => [ //настройка цвета шрифта (опционально)
            'R' => 0, //красный (обязательно)
            'G' => 0, //зеленый (обязательно)
            'B' => 0, //синий (обязательно)
            'A' => 0 //альфа канал (опционально)
        ],
        'bg' => [ //настройка цвета фона (опционально)
            'R' => 0, //красный (обязательно)
            'G' => 0, //зеленый (обязательно)
            'B' => 0, //синий (обязательно)
            'A' => 0 //альфа канал (опционально)
        ]
    ]); ?>
    
    <!-- обычный вывод изображения -->
    <?php echo $this->captcha->getImage(); ?>
    
    <br>
    <!-- вывод текстового поля -->
    <?php echo $this->form->render('captcha', [
        'placeholder' => 'Введите цифры с картинки
    ']); ?>
<?php elseif ( $this->captcha->getAdapterName() === 'Recaptcha' ): ?>
    <!-- отобразит тёмный Recaptcha Widget -->
    <?php echo $this->form->render('captcha', [
        'theme' => 'dark'
    ']); ?> 
<?php endif; ?>
```

## Требования
Phalcon ^3.3.0

PHP ^7.2.0

## Автор
Timur Flush

Telegram: @flush02

## Лицензия
Apache 2.0 License