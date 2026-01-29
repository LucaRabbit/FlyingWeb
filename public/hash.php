<!-- Mot de passe pour accéder à l'espace d'administration -->
<?php
echo password_hash("AdminPass", PASSWORD_DEFAULT);