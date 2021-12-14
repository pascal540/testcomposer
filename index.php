<?php
require 'vendor/autoload.php';

use App\GuestBook;
use App\Message;

//require_once 'class/Message.php';
//require_once 'class/GuestBook.php';

$errors = null;
$success = false;
$guestbook = new GuestBook(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'messages');
if (isset($_POST['username'], $_POST['message'])) {
    $message = new Message($_POST['username'], $_POST['message']);
    if ($message->isValid()) {
        $guestbook->addMessage($message);
        $success = true;
        $_POST = [];
    } else {
        $errors = $message->getErrors();
    }
}

$messages = $guestbook->getMessages();
dump($messages);
require 'elements/header.php'; 
?>

<div class="container col-sm-3">
    <br>
    <h2>Livre d'Or</h2>
    <br>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            Formulaire invalide
        </div>
    <?php endif ?>

    <?php if ($success): ?>
        <div class="alert alert-success">
            Merci pour votre message
        </div>
    <?php endif ?>

    <form action="" method="POST">
        <div class="form-group">
            <input class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" type="text" 
            name="username" placeholder="Entrez votre pseudo" value="<?= htmlentities($_POST['username'] ?? '') ?>">
            <?php if (isset($errors['username'])): ?>
            <div class="invalid-feedback"><?= $errors['username'] ?></div>
            <?php endif ?>
            <br>
        </div>
        <div class="form-group">
            <textarea class="form-control <?= isset($errors['message']) ? 'is-invalid' : '' ?>" 
            name="message" placeholder="Entrez votre message"><?= htmlentities($_POST['message'] ?? '') ?></textarea>
            <?php if (isset($errors['message'])): ?>
            <div class="invalid-feedback"><?= $errors['message'] ?></div>
            <?php endif ?>
            <br>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
    <br>

    <?php if (!empty($messages)): ?>
    <h3>Vos Messages</h3>
    <?php foreach($messages as $message): ?>
        <?= $message->toHTML() ?>
    <?php endforeach ?>
    <?php endif ?>
</div>

<?= dd($messages) ?>
<?php require 'elements/footer.php';