<?php
function nav_bar_html($atts)
{
    $a = shortcode_atts(array('url' => '',), $atts);
    $link = wp_logout_url($a["url"]);

    ob_start(); ?>

    <style>
        .nav-link {
            display: flex;
            align-content:center
        }
        /*efeito de escala no nav-link:hover */
        .nav-link:hover {
            transform: scale(1.1);
            transition: transform 0.5s;
        }
        </style>
    <button class="btn btn lh-1 fs-2 text-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
        aria-controls="offcanvasNavbar">
        <span class="game-icons--flower-twirl"></span>
    </button>
    <div class="offcanvas offcanvas-end" style="width:300px;" tabindex="-1" id="offcanvasNavbar"
        aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header mt-5 p-0 px-3" style="border-bottom:3px solid #f00">
            <h2 class="offcanvas-title font-t20" id="offcanvasNavbarLabel">Metaverso</h2>
            <button type="button" data-bs-dismiss="offcanvas" class="btn btn-close" aria-label="Close" style=" border-radius:20px"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/"><span class="game-icons--dice-twenty-faces-twenty mx-2"></span> Home</a>
                </li>
                <?php if (is_user_logged_in()) { ?>
                    <li class="nav-item">
                        <a href="<?php echo $link ?>" class="btn-logout nav-link"> <span class="game-icons--crypt-entrance mx-2"></span>  <strong>Sair</strong></a>
                    </li>
                    <li class="nav-item ">
                        <a href="/personagens" class="nav-link"><span class="game-icons--gooey-daemon mx-2"></span> Personagens</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false"><span class="game-icons--boomerang-sun mx-2"></span>
                            Configurações
                        </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item">
                                <div class="input-group">
                                    <spam class="form-control">Excluir dados do jogo em cache?</spam>
                                    <button class="btn btn-danger" onclick="excluirDadosCache()">Sim!</button>
                                </div>
                            </li>
                        </ul>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/entrar"><span class="game-icons--guards mx-2"></span> <strong>Login</strong></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <script>
        function excluirDadosCache() {
            localStorage.removeItem("pericias");
            localStorage.removeItem("racas");
            localStorage.removeItem("habilidades_raca");
            localStorage.setItem("notificacao", JSON.stringify({
                tipo: "success",
                mensagem: `Os dados salvos em cache foram apagados.`
            }));
            location.reload(true);

        }
    </script>
<?php
    return ob_get_clean();
}
add_shortcode('nav_bar', 'nav_bar_html');
