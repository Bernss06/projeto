<?php

/** @var yii\web\View $this */
use yii\helpers\Url;
$this->title = 'Gerencie suas Coleções com Facilidade';
?>

  <section class="hero text-center py-5">
    <div class="container">
      <div class="tagline-badge mb-3">
        <span class="tagline-text, text-secondary">Organize suas coleções de forma inteligente</span>
      </div>
      <h1 class="fw-bold text-light">
        <span class="text-gradient">Gira as suas Coleções com <br>Facilidade</span>
      </h1>
      <p class="lead mb-5 text-secondary">
        Crie, organize e compartilhe suas coleções favoritas – mantenha tudo organizado em um só lugar.
      </p>
      <div>
        <a href="/projeto/frontend/web/site/signup" class="btn btn-primary me-2">
          Criar Conta Grátis
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left: 8px;">
            <path d="M6 12L10 8L6 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
        <a href="#" class="btn btn-outline-light">Explorar Coleções Públicas</a>
      </div>
    </div>
  </section>

  <section class="features py-5">
    <div class="container">
      <div class="row text-center g-4">
        <div class="col-md-4">
          <div class="card bg-dark border-secondary h-100">
            <div class="card-body text-center">
              <div class="feature-icon mb-3 icon-purple">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <rect x="3" y="3" width="5" height="5" />
                  <rect x="10" y="3" width="5" height="5" />
                  <rect x="17" y="3" width="5" height="5" />
                  <rect x="3" y="10" width="5" height="5" />
                  <rect x="10" y="10" width="5" height="5" />
                  <rect x="17" y="10" width="5" height="5" >
                  <rect x="3" y="17" width="5" height="5" />
                  <rect x="10" y="17" width="5" height="5" />
                  <rect x="17" y="17" width="5" height="5" />
                </svg>
              </div>
              <h5 class="card-title text-light">Organize Tudo</h5>
              <p class="card-text text-secondary">Crie coleções personalizadas com categorias, descrições e imagens. Mantenha seus itens organizados e fáceis de encontrar.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-dark border-secondary h-100">
            <div class="card-body text-center">
              <div class="feature-icon mb-3 icon-cyan">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M18 8C19.6569 8 21 9.34315 21 11C21 12.6569 19.6569 14 18 14M6 15C4.34315 15 3 13.6569 3 12C3 10.3431 4.34315 9 6 9M18 8C16.3431 8 15 6.65685 15 5C15 3.34315 16.3431 2 18 2C19.6569 2 21 3.34315 21 5C21 6.65685 19.6569 8 18 8ZM6 9C7.65685 9 9 7.65685 9 6C9 4.34315 7.65685 3 6 3C4.34315 3 3 4.34315 3 6C3 7.65685 4.34315 9 6 9ZM18 14C16.3431 14 15 15.3431 15 17C15 18.6569 16.3431 20 18 20C19.6569 20 21 18.6569 21 17C21 15.3431 19.6569 14 18 14ZM6 15C7.65685 15 9 16.3431 9 18C9 19.6569 7.65685 21 6 21C4.34315 21 3 19.6569 3 18C3 16.3431 4.34315 15 6 15Z" stroke="cyan" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <h5 class="card-title text-light">Compartilhe</h5>
              <p class="card-text text-secondary">Torne suas coleções públicas para que outros possam ver e se inspirar. Ou mantenha-as privadas apenas para você.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-dark border-secondary h-100">
            <div class="card-body text-center">
              <div class="feature-icon mb-3 icon-yellow">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M20.84 4.61C20.3292 4.099 19.7228 3.69364 19.0554 3.41708C18.3879 3.14052 17.6725 2.99817 16.95 2.99817C16.2275 2.99817 15.5121 3.14052 14.8446 3.41708C14.1772 3.69364 13.5708 4.099 13.06 4.61L12 5.67L10.94 4.61C9.9083 3.57831 8.50903 2.99871 7.05 2.99871C5.59096 2.99871 4.19169 3.57831 3.16 4.61C2.1283 5.64169 1.54871 7.04097 1.54871 8.5C1.54871 9.95903 2.1283 11.3583 3.16 12.39L4.22 13.45L12 21.23L19.78 13.45L20.84 12.39C21.351 11.8792 21.7564 11.2728 22.0329 10.6054C22.3095 9.93789 22.4518 9.22248 22.4518 8.5C22.4518 7.77752 22.3095 7.0621 22.0329 6.39464C21.7564 5.72718 21.351 5.12075 20.84 4.61Z" stroke="yellow" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <h5 class="card-title text-light">Favorite</h5>
              <p class="card-text text-secondary">Adicione suas coleções favoritas e as de outros usuários aos favoritos para acesso rápido.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="collections py-5">
    <div class="container">
      <h2 class="text-center mb-4">Coleções em Destaque</h2>
      <div class="row justify-content-center">
        <div class="col-md-3">
        <div class="card bg-dark border-secondary">
      <!--<div class="card-header bg-gradient" style="background: linear-gradient(90deg, #00c6ff, #0072ff);"></div>
            <div class="card-body text-center">
              <h5 class="card-title text-light">Monsters</h5>
              <p class="card-text text-secondary">20 Itens</p>
              <a href="#" class="btn btn-outline-light btn-sm">Ver Coleção</a>
            </div>
          </div>-->
        </div>
      </div>
      <div class="text-center mt-4">
        <a href="#" class="btn btn-outline-primary">Ver Todas as Coleções Públicas</a>
      </div>
    </div>
  </section>

  <section class="cta py-5">
    <div class="container text-center">
          <div class="p-5 cta-wrapper-dark text-light">
        <h3 class="mb-3 text-light">Pronto para começar?</h3>
              <p class="mb-4 text-secondary">Crie sua conta gratuitamente e comece a organizar suas coleções hoje mesmo.</p>
              <a href="<?= Url::to(['site/signup']) ?>" class="btn btn-primary">
            Criar Conta Grátis
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left: 8px;">
              <path d="M6 12L10 8L6 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
      </div>
    </div>
  </section>
