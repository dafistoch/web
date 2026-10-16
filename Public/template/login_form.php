<?php

$error_message = NULL;
if ($erreur === 'login_passwd_incorrect') {
    $error_message = _('login_passwd_incorrect');
} elseif ($erreur==='login_non_connu') {
    $error_message = _('login_non_connu');
} elseif( 'session-invalid' === getpost_variable('error', false)){
    $error_message = _('session_pas_session_ouverte');
}

$logoAssociation = null;
try {
    $configLogin = new \App\Libraries\Configuration(\includes\SQL::singleton());
    $cheminLogo = trim($configLogin->getLogoAssociation());
    $isUrlLogo = (bool) preg_match('#^(https?:)?//#i', $cheminLogo);
    if ('' !== $cheminLogo && ($isUrlLogo || is_file(ABSOLUTE_SYSPATH . ltrim($cheminLogo, '/')))) {
        $logoAssociation = [
            'src' => $isUrlLogo ? $cheminLogo : ROOT_PATH . ltrim($cheminLogo, '/'),
            'largeur' => $configLogin->getLogoAssociationLargeur(),
            'hauteur' => $configLogin->getLogoAssociationHauteur(),
        ];
    }
} catch (\Exception $e) {
    $logoAssociation = null;
}
?>
<div class="container">
    <div class="form-signin">
    <?php if($error_message): ?>
        <div class="alert alert-warning">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?= $error_message ?>
        </div>
    <?php endif;
    if(preg_match('/MSIE/i',$_SERVER['HTTP_USER_AGENT'])):?>
        <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?= _('ie_non_gere') ?>
        </div>
    <?php endif; ?>

    <?php if ($logoAssociation): ?>
        <div class="logo-association">
            <img src="<?= htmlspecialchars($logoAssociation['src'], ENT_QUOTES) ?>" alt="Logo de l'association" width="<?= $logoAssociation['largeur'] ?>" height="<?= $logoAssociation['hauteur'] ?>" style="width: <?= $logoAssociation['largeur'] ?>px; height: <?= $logoAssociation['hauteur'] ?>px;">
        </div>
    <?php endif; ?>
    <form method="post" action="<?= $PHP_SELF ?>">
        <h2 class="form-signin-heading">Connexion</h2>
        <input type="text" id="session_username" class="form-control" name="session_username" value="<?= $session_username ?>" placeholder="<?= _('divers_login_maj_1') ?>" autofocus>
        <input type="password" id="session_password" class="form-control" name="session_password" placeholder="Mot de passe" autocomplete="off"/>
        <button type="submit" class="btn btn-lg btn-primary btn-block"><?= _('form_submit') ?></button>
    </form>
    </div>
</div>
