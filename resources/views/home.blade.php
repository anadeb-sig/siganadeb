<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ANADEB</title>

        <style>

            .feature {
                padding-top: 170px;
            }
            .spad {
                padding-top: 100px;
                padding-bottom: 100px;
            }
            
            @media (min-width: 768px) {
                .container {
                    max-width: 720px;
                }
            }
            @media (min-width: 576px) {
                .container {
                    max-width: 80%;
                }
            }
            .container {
                width: 100%;
                padding-right: var(--bs-gutter-x, .75rem);
                padding-left: var(--bs-gutter-x, .75rem);
                margin-right: auto;
                margin-left: auto;
                position: relative;
                z-index: 9;
            }
            
            
            div {
                display: block;
                unicode-bidi: isolate;
            }
            body, html {
                height: 100%;
                font-family: Mulish, sans-serif;
                -webkit-font-smoothing: antialiased;
            }
            .justify-content-center {
                justify-content: center !important;
            }
            .d-flex {
                display: flex !important;
            }
            .row {
                --bs-gutter-x: 1.5rem;
                --bs-gutter-y: 0;
                display: flex;
                flex-wrap: wrap;
                margin-top: calc(var(--bs-gutter-y)* -1);
                margin-right: calc(var(--bs-gutter-x) / -2);
                margin-left: calc(var(--bs-gutter-x) / -2);
            }
            *, ::after, ::before {
                box-sizing: border-box;
            }
            .text-center {
                /*text-align: center !important;*/
            }
            @media (min-width: 992px) {
                .col-lg-7 {
                    flex: 0 0 auto;
                    width: 58.3333333333%;
                }
            }
            .row>* {
                flex-shrink: 0;
                width: 100%;
                max-width: 100%;
                padding-right: calc(var(--bs-gutter-x) / 2);
                padding-left: calc(var(--bs-gutter-x) / 2);
                margin-top: var(--bs-gutter-y);
            }
            
            h2 {
                font-size: 40px;
            }
            h2, h4, h5 {
                margin: 0;
                color: #111;
                font-family: Mulish, sans-serif;
                line-height: 1.2;
            }
            
            @media (min-width: 768px) {
                .col-md-6 {
                    flex: 0 0 auto;
                    width: 50%;
                }
            }

            @media (min-width: 576px) {
                .col-sm-6 {
                    flex: 0 0 auto;
                    width: 50%;
                }
            }
            .feature__item__icon {
                float: left;
                margin-right: 16px;
            }

            img {
                max-width: 100%;
                height: auto;
            }

            img, svg {
                vertical-align: middle;
            }

            .feature__item__text {
                overflow: hidden;
                padding-top: 5px;
            }
            
            .feature__item__text h4 {
                color: #1f3e64;
                font-size: 22px;
                font-weight: 800;
                margin-bottom: 14px;
                -webkit-transition: none, .3s;
                -moz-transition: none, .3s;
                -ms-transition: none, .3s;
                -o-transition: none, .3s;
                transition: all, .3s;
            }
            h4 {
                font-size: 24px;
            }
            .feature__item__text p {
                font-size: 16px;
                line-height: 1.7;
                margin-bottom: 0;
                color: #687083;
            }

            p {
                display: block;
                margin-block-start: 1em;
                margin-block-end: 1em;
                margin-inline-start: 0px;
                margin-inline-end: 0px;
                unicode-bidi: isolate;
                margin-top: 0;
                color: #687083;
                text-align: justify;
            }
            
            .section-title {
                margin-bottom: 85px;
                text-align: center;
            }
            .section-title h2 {
                font-size: 35px;
                font-weight: 800;
                color: #1f3e64;
                line-height: 1.45;
            }

            .feature__item {
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                padding: 20px;
                /*text-align: center;*/
                transition: transform 0.3s;
                margin-bottom: 84px;
            }

            .feature__item:hover {
                transform: translateY(-10px);
            }

            .feature__item__icon .icon {
                height: 67px;
                width: 67px;
                border: 1px solid #ebdeda;
                line-height: 67px;
                text-align: center;
                border-radius: 50%;
                margin-bottom: 20px;
            }


            @media (max-width: 768px) {
                .col-lg-4, .col-md-6, .col-sm-6 {
                    margin-bottom: 30px;
                }
            }
            a{
                text-decoration: navajowhite;
            }
        </style>
    </head>
    <body>
        <section class="feature spad">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-7 text-center">
                        <div class="section-title">
                            <h2>Programme / Projet d'ANADEB</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Premier élément -->
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        <a href="{{ Route('home_infra') }}">
                            <div class="feature__item">
                                <div class="feature__item__icon">
                                    <div class="icon">
                                        <img width="21" height="27" src="https://adminlte.io/wp-content/uploads/2021/04/f-1.png" alt="HTML5 & CSS3">
                                    </div>
                                </div>
                                <div class="feature__item__text">
                                    <h4>Inrastructures communautaires</h4>
                                    <p> Soutenir la réhabilitation et la construction d’infrastructures de base dans les communautés ciblées, ce qui permettra d’accroître l’accès aux services socioéconomiques afférents.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Troisième élément -->
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        <a href="{{ Route('home_cantine') }}">
                            <div class="feature__item">
                                <div class="feature__item__icon">
                                    <div class="icon">
                                        <img width="31" height="26" src="https://adminlte.io/wp-content/uploads/2021/04/f-3.png" alt="1000+ Icons">
                                    </div>
                                </div>
                                <div class="feature__item__text">
                                    <h4>Cantine scolaire</h4>
                                    <p>Accroitre l’accès des enfants des communautés les plus pauvres du Togo à des repas scolaires réguliers, ce qui devrait en retour améliorer la fréquentation et la rétention dans les écoles des zones ciblées.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Deuxième élément -->
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        <a href="{{ Route('home_fsb') }}">
                            <div class="feature__item">
                                <div class="feature__item__icon">
                                    <div class="icon">
                                        <img width="31" height="26" src="https://adminlte.io/wp-content/uploads/2021/04/f-2.png" alt="Responsive Design">
                                    </div>
                                </div>
                                <div class="feature__item__text">
                                    <h4>Filets sociaux et services de base (FSB)</h4>
                                    <p>Assurer aux ménages et communautés pauvres un meilleur accès aux infrastructures socioéconomiques de base et aux filets sociaux.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Troisième élément -->
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        <a href="">
                            <div class="feature__item">
                                <div class="feature__item__icon">
                                    <div class="icon">
                                        <img width="33" height="26" src="https://adminlte.io/wp-content/uploads/2021/04/f-4.png" alt="" data-lazy-src="https://adminlte.io/wp-content/uploads/2021/04/f-4.png" data-ll-status="loaded" class="entered lazyloaded">
                                    </div>
                                </div>
                                <div class="feature__item__text">
                                    <h4>Autres</h4>
                                    <p>D'avenir.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>
