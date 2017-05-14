<?php
/**
 * Verifies an email exists
 *
 * Please report bugs on https://github.com/espino316/rsphp/issues
 *
 * @author Luis Espino <luis@espino.info>
 * @copyright Copyright (c) 2016, Luis Espino. All rights reserved.
 * @license MIT License
 */
class EmailVerifier {

    //  Es el email que vamos a verificar
    private $email;

    //  Esta es la conexión al servidor smtp
    private $smtpConn;

    //  El log, los comandos y las respuestas
    private $recordLog;

    //  La dirección del servidor SMTP
    private $server;

    //  Guarda la ultima respuesta del servidor
    private $lastResponse;

    //  Guarda el resultado, si es válido o no
    //  Lo inicializamos para comenzar con los comandos
    //  y comparar los resultados
    public $isValid = TRUE;

    /**
     *  Crea una nueva instancia de EmailVerifier
     *  @param $email el email a verificar
     */
    function __construct( $email ) {
        //  Establecemos los valores
        $this->email = $email;
        $this->recordLog = '';
    } // end function __construct

    /**
     *  Regresa la parte izquierda de la cadena
     */
    static function left($str, $length) {
        return substr($str, 0, $length);
    } // end function left

    /**
     *  Verifica que el email exista
     *  Establece una conexión y envia una serie de comandos
     *  para probar la dirección de correo
     */
    function verify() {
        try {
            //  Creamos la conexión
            $this->createSMTPConnection();
            //  Primer HELO, es como iniciar el telnet
            $this->sendCommand("HELO $this->server");
            //  Segundo HELO, este si lo cacha el servidor SMTP
            $this->sendCommand("HELO $this->server");
            //  Establecemos el FROM
            //  El FROM no necesariamente tiene que ser un correo válido en el sistema
            $this->sendCommand("MAIL FROM: <$this->email>");
            //  Establecemos el destinatario
            //  Aqui verifica que el correo exista de verdad
            $this->sendCommand("RCPT TO: <$this->email>");
            //  Salimos
            $this->sendCommand("QUIT");

            //  Cerramos
            return $this->close();

        } catch ( Exception $ex ) {
            $this->isValid = FALSE;
            $this->addLog( "error  ".$ex->getMessage() );
            return FALSE;
        } // end catch
    } // end function verify

    /**
     *  Cierra la conexión, imprime los comandos y las respuestas
     *  y regresa el resultado
     */
    private function close() {
        //  Cerramos la conexión
        $this->closeSMTPConnection();
        //  Regresamos el resultado
        return $this->isValid;
    } // end function close

    /**
     *  Agrega una linea de texto al log
     */
    function getLog() {
        return $this->recordLog;
    } // end function addLog

    /**
     *  Agrega una linea de texto al log
     */
    private function addLog( $logMessage ) {
        $logMessage.=NEW_LINE;
        $this->recordLog.= $logMessage;
    } // end function addLog

    /**
     *  Cierra la conexión SMTP
     */
    private function closeSMTPConnection() {
        fclose( $this->smtpConn );
        return;
    } // end function closeSMTPConnection

    /**
     *  Envía el comando y procesamos la respuesta
     */
    private function sendCommand( $command ) {

        //  Solo si aun es válido
        //  si no, ya no realiza los comandos
        //  Puesto que ya es inválido
        if ( !$this->isValid ) {
            return true;
        }

        //  Agregamos el comando al log
        $command.= NEW_LINE;
        $this->addLog( $command );

        //  Enviamos el comando
        fwrite($this->smtpConn, $command);

        //  Obtenemos la respuesta
        $this->getResponse();

        //  Procesamos la respuesta
        $this->parseResponse();

        //  Regresamos que el comando se envío correctamente
        //  no el resultado
        return true;
    }

    /**
     *  Procesa la respuesta
     */
    private function parseResponse() {

        //  Obtenemos el codigo de la respuesta
        $response = $this->left( $this->lastResponse, 3 );
        //  Estas son las válidas
        $ok = array( 220, 221, 250);

        //  Si está en las válidas
        if ( in_array( $response, $ok ) ) {
            //  Es válido
            $this->isValid = TRUE;
        } else {
            //  No es válido
            $this->isValid = FALSE;
        } // end if
    } // end function parseResponse

    /**
     *  Obtiene la respuesta
     */
    private function getResponse() {

        //  Aqui almacenaremos la respuesta
        $output = '';
        //  Mientras haya conexión y mande datos
        while ( is_resource( $this->smtpConn ) && !feof( $this->smtpConn ) ) {
            //  Obtenemos una linea
            $line = @fgets( $this->smtpConn, 515);
            //  La ponemos en el buffer
            $output.= $line;
            //  Si el cuarto caracter es espacio, se termina
            if ((isset($line[3]) and $line[3] == ' ')) {
                break;
            } // end if $line[3]
        } // end while is_resource

        //  Agregamos la salida al log
        $this->addLog( $output );

        //  Si la respuesta que obtuvimos es la misma
        //  que la ultima, volvemos a obtener la respuesta
        if ( $this->lastResponse ) {
            if ( $this->lastResponse == $output ) {
                $output = '';
                while ( is_resource( $this->smtpConn ) && !feof( $this->smtpConn ) ) {
                    $line = @fgets( $this->smtpConn, 515);
                    $output.= $line;
                    if ((isset($line[3]) and $line[3] == ' ')) {
                        break;
                    }
                }
                $this->addLog( $output );
            }
        }

        //  Ahora si la ponemos en la última respuesta
        $this->lastResponse = $output;
        //  Regresamos la respuesta
        return $output;
    } // end function getResponse

    /**
     *  Crea una conexión SMTP
     */
    private function createSMTPConnection() {
        //  Obtenemos las partes del email
        $parts = explode('@',$this->email);
        //  La segunda parte es el dominio
        $domain = $parts[1];

        //  Obtenemos los records mx
        $mxhosts = array();
        $mxweights = array();
        $isMx = getmxrr($domain, $mxhosts, $mxweight);

        if ( empty( $mxhosts ) ) {
            throw new Exception( 'No mx records' );
        }

        //  El dominio, el servidor al que vamos a apuntar
        //  es el primer registro mx
        $domain = $mxhosts[0];

        //  El puerto es el 25
        $port = 25;

        //  Obtenemos la dirección IP
        $address = gethostbyname($domain);

        //  formamos la dirección con protocolo, ip y puerto
        $to = "tcp://$address:$port";
        //  Intentamos crear el socket
        $this->smtpConn = stream_socket_client($to, $errno, $errorMessage, 10);

        //  Si no se logro conectar
        //  hay un error
        if ( $this->smtpConn === false ) {
            throw new UnexpectedValueException("Connection failed, error: $errorMessage");
        } // end if

        //  Establecemos el servidor
        $this->server = $domain;

        //  True por que nos conectamos
        return true;
    } // end createSMTPConnection
} // end class EmailVerifier