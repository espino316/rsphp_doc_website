<div class="content content-min-height">
  <h1>Envío de Correos Electrónicos</h1>
  <p>
    Procedemos a configurar el correo. Lo podemos hacer de dos formas, mediante app.json, configurando como variables
    globales los parámetros, o pasando los argumentos directamente a la clase, mediante un arreglo. Los parámetros mandatorios
    son: <b>servidor, usuario y password</b>. El <b>puerto</b> es opcional.
  </p>
  <p>
    Para configurarlo globalmente, en <i>/config/app.json:</i>
  </p>
  <pre>
    <code class="json">
      {
        "globals": {
          "MAIL_SERVER": "mail.midominio.com",
          "MAIL_USER": "micorreo@midominio.com",
          "MAIL_PWD": "micontraseñasupersegura"
        }
      }
    </code>
  </pre>
  <p>
    Para configurarlo pasandole un arreglo a la clase:
  </p>
  <pre>
    <code class="php">
      Mailer::setConfig(
        array(
          "mailServer" => "mail.midominio.com",
          "mailUser" => "micorreo@midominio.com",
          "mailPassword" => "micontraseñasupersegura"
        )
      );
    </code>
  </pre>
  <h2 id="send">Envíar correos</h2>
  <p>
    <b>Mailer</b>es una clase estática a la que configuramos los datos de envío directamente, de la siguiente manera:
  </p>
  <pre>
    <code class="php">
      //  Simplemente configuramos cada dato del email
      //  Remitente
      Mailer::$from = 'luis@espino.info';
      //  Destinatario
      Mailer::$to = $customerData['email'];
      //  Asunto
      Mailer::$subject = 'Cuenta creada en miasombrosositio.com';
      //  Mensaje
      Mailer::$message = 'Tu cuenta ha sido creada!';
      //  Indicamos si el mensaje es php ( true or false )
      Mailer::$html = true;
      //  Mandamos el mensaje con send
      Mailer::send();
    </code>
  </pre>
  <p>
    Si deseamos agregar datos adjuntos, utilizamos el método <b>addAttachment</b>, que tiene por parámetro <b>$filePath</b>
    que es la ruta del archivo que deseamos adjuntar, antes de enviar. Por lo tanto, lo utilizamos de la siguiente manera:
  </p>
  <pre>
    <code class="php">
      //  Simplemente configuramos cada dato del email
      //  Remitente
      Mailer::$from = 'luis@espino.info';
      //  Destinatario
      Mailer::$to = $customerData['email'];
      //  Asunto
      Mailer::$subject = 'Cuenta creada en miasombrosositio.com';
      //  Mensaje
      Mailer::$message = 'Tu cuenta ha sido creada!';
      //  Indicamos si el mensaje es php ( true or false )
      Mailer::$html = true;
      //  Ajuntamos archivos:
      Mailer::addAttachment( '/home/luis/attachment1.jpg' );
      Mailer::addAttachment( '/home/luis/attachment2.pdf' );
      //  Mandamos el mensaje con send
      Mailer::send();
    </code>
  </pre>
</div>
