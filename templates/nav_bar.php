
<?php
function nav_bar_html($atts)
{
    $a = shortcode_atts( array('url' => '',  ), $atts);
    $link = wp_logout_url($a["url"]);
    $link = '<a href="'.$link.'" class="btn-logout nav-link"><strong>Sair</strong></a>';

    ob_start(); ?>
<button class="btn btn lh-1 fs-2 text-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="game-icons--flower-twirl"></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header mt-4 justify-content-between">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu de Navegação</h5>
                    <button type="button" data-bs-dismiss="offcanvas" class="btn btn-close" aria-label="Close" style=" border-radius:20px"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/">Home</a>
                        </li>
                        <?php if ( is_user_logged_in() ) { ?>
                        <li class="nav-item">
                            <?php echo $link ?>
                        </li>
                        <li class="nav-item ">
                        <a href="/personagens" class="row align-content-center"><span class="game-icons--robe mx-2"></span> Personagens</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Configurações
                            </a>
                            <ul class="dropdown-menu" >
                                <li class="dropdown-item">
                                    <div class="input-group">
                                        <spam class="form-control">Excluir dados do jogo em cache?</spam>
                                        <button onclick="excluirDadosCache()">Sim!</button>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/entrar">Login</a>
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