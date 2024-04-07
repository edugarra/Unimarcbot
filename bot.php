<?php
$token = 'tu_token';
$website = 'https://api.telegram.org/bot'.$token;

$input = file_get_contents('php://input');
$update = json_decode($input, true);

// se definen los pasillos y los productos
$pasillos = array(
    "1" => array("Carne", "Queso", "Jamón"),
    "2" => array("Leche", "Yogurth", "Cereal"),
    "3" => array("Bebidas", "Jugos"),
    "4" => array("Pan", "Pasteles", "Tortas"),
    "5" => array("Detergente", "Lavaloza")
);

// se crea la función para enviar mensajes de texto al chat de Telegram
function enviarMensaje($chat_id, $mensaje) {
    global $website;
    $url = $website . '/sendMessage?chat_id=' . $chat_id . '&text=' . urlencode($mensaje);
    file_get_contents($url);
}

if (isset($update["message"])) {
    $chat_id = $update["message"]["chat"]["id"];
    $message_text = $update["message"]["text"];

    if ($message_text === '/start') {
        enviarMensaje($chat_id, "¡Hola! Soy el bot del supermercado Unimarc. ¿En qué puedo ayudarte?");
    } else {
        $encontrado = false;
        foreach ($pasillos as $pasillo => $productos) {
            if (in_array(strtolower($message_text), array_map('strtolower', $productos))) {
                enviarMensaje($chat_id, "Los productos $message_text se encuentran en el pasillo $pasillo.");
                $encontrado = true;
                break;
            }
        }
        if (!$encontrado) {
            enviarMensaje($chat_id, "Lo siento, no entendí tu pregunta.");
        }
    }
}
?>
