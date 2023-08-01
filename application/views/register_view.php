<!DOCTYPE html>
<html>
<head>
    <title>Crear Cuenta</title>
    <style>
        body {
            background: linear-gradient(to bottom right, #330033, #000);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        .container {
            background-color: #333;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.2);
            width: 400px;
        }

        h1 {
            color: #fff;
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .input-container {
            position: relative;
            margin-bottom: 20px;
        }

        input {
            border: none;
            background-color: transparent;
            border-bottom: 2px solid #fff;
            width: 100%;
            padding: 10px 0;
            color: #fff;
            font-size: 16px;
        }

        label {
            position: absolute;
            top: 10px;
            left: 0;
            color: #fff;
            font-size: 16px;
            pointer-events: none;
            transition: 0.3s ease all;
        }

        .bar {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #00ff00; /* Color LED */
            transform: scaleX(0);
            transition: 0.3s ease all;
        }

        input:focus ~ .bar,
        input:valid ~ .bar {
            transform: scaleX(1);
        }

        button {
            border: none;
            background-color: #00ff00; /* Color LED */
            color: #000;
            font-size: 18px;
            padding: 10px 20px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s ease all;
        }

        button:hover {
            background-color: #00dd00; /* Color LED */
            box-shadow: 0 0 10px #00ff00; /* Color LED */
        }

        .no-account {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
            color: #fff;
        }

        .no-account a {
            color: #00ff00; /* Color LED */
            text-decoration: none;
        }

        .no-account a:hover {
            color: #00dd00; /* Color LED */
        }
        label.active {
        transform: translateY(-30px);
    }
    </style>
     <script>
    function moveLabel(input) {
        const label = input.previousElementSibling;
        if (label) {
            label.classList.toggle('active', input.value.trim() !== '' || document.activeElement === input);
        }
    }
    </script>
</head>
<body>

    <div class="container">
        <h1>Crear Cuenta</h1>
        <form action="<?= base_url('auth/register'); ?>" method="post" >
            <div class="input-container">
            <label for="username">Usuario</label>
                <input type="text" id="username" name="username" required onfocus="moveLabel(this)" onblur="moveLabel(this)">
               
                <div class="bar"></div>
            </div>
            <div class="input-container">
            <label for="email">Email</label>
                <input type="email" id="email" name="email" required onfocus="moveLabel(this)" onblur="moveLabel(this)">
                
                <div class="bar"></div>
            </div>
            <div class="input-container">
            <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required onfocus="moveLabel(this)" onblur="moveLabel(this)">
                
                <div class="bar"></div>
            </div>
            <div class="input-container">
    <input type="file" id="profile_image" name="profile_image" accept="image/*" enctype="multipart/form-data">
</div>
            <button type="submit">Crear Cuenta</button>
        </form>
        <div class="no-account">
            ¿Ya tienes una cuenta? <a href="<?= base_url('auth'); ?>">Iniciar Sesión</a>
        </div>
    </div>
</body>
<!-- ... (código posterior) ... -->

</body>
</html>


