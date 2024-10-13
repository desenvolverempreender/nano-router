<?php
session_start();
require 'src/Router/Router.php';


// Definindo a base path '/router'
$router = new Nano\Router\Router('/router');

// Simulação de um "banco de dados" com credenciais
$users = [
    'admin@example.com' => 'admin123',
    'user@example.com' => 'user123'
];

// Redirecionar a rota raiz para /login usando $router->redirect
$router->redirect('/', '/router/login');

// Exibir o formulário de login
$router->get('/login', function() {
    echo '
    <form action="/router/login" method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Senha:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>';
});

// Processar login (método POST)
$router->post('/login', function() use ($users) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verificar credenciais
    if (isset($users[$email]) && $users[$email] === $password) {
        $_SESSION['user'] = $email;
        header("Location: /router/dashboard");
        exit();
    } else {
        echo '<p>Email ou senha incorretos!</p>';
        echo '<a href="/router/login">Tente novamente</a>';
    }
});

// Dashboard protegido (somente para usuários logados)
$router->get('/dashboard', function() {
    if (!isset($_SESSION['user'])) {
        header("Location: /router/login");
        exit();
    }

    echo "<h1>Bem-vindo, {$_SESSION['user']}!</h1>";
    echo '<p><a href="/router/logout">Sair</a></p>';
});

// Logout (finalizar sessão)
$router->get('/logout', function() {
    session_destroy();
    header("Location: /router/login");
    exit();
});

// Tratamento de exceção para rotas não encontradas
try {
    $router->dispatch();
} catch (Nano\RouteNotFoundException $e) {
    http_response_code(404);
    echo 'Página não encontrada';
}
