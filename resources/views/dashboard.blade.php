<!doctype html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>作業日報管理システム【らくラクポチッと日報】</title>
    <meta name="description" content="建設・機械器具設置・電気工事の現場作業員向け 作業日報をもっと簡単に。現場情報の一元管理、作業日報の記録、QR入力対応レポート。" />

    <!-- Google Fonts: Noto Sans JP -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap"
        rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
</head>

<body>
    <!-- Header -->
    <header class="site-header" id="top">
        <div class="container header-inner">
            <a class="brand" href="#top" aria-label="トップへ">
                <img src="{{ asset('images/らくラク！ポチッと日報.png') }}" alt="らくラクポチッと日報 ロゴ" class="brand-logo" />
                <span class="brand-name">らくラクポチッと日報</span>
            </a>

            <nav class="site-nav" aria-label="メインナビゲーション">
                <button class="nav-toggle" aria-expanded="false" aria-controls="nav-menu" aria-label="メニューを開閉">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>
                <ul id="nav-menu" class="nav-menu">
                    <li><a href="#features-intro">機能紹介</a></li>
                    <li><a href="#features-list">機能一覧</a></li>
                    <li><a href="#contact">お問い合わせ</a></li>
                    <li id="nav-user" class="nav-user" style="display:none;"><i class="fa-solid fa-user"
                            aria-hidden="true"></i> <span id="userNameText"></span></li>
                    <li><a id="navLoginLink" href="{{ route('login') }}">ログイン</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">ログアウト</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero -->
    <section class="hero" id="hero">
        <div class="container hero-inner">
            <div class="hero-copy reveal">
                <h1>作業日報を、もっと簡単に</h1>
                <p class="subtitle">作業日報をリモート入力</p>
                <div class="hero-ctas">
                    <a href="#contact" class="btn btn-primary btn-lg">お問い合わせ</a>
                    <a href="#features-intro" class="btn btn-ghost btn-lg">機能を見る</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Intro (icons + short text) -->
    <section class="section" id="features-intro">
        <div class="container" style="display:grid;place-items:center">
            <h2 class="section-title reveal">すぐに使える主要機能</h2>
            <div class="features-grid" style="width:min(1120px,92%);">
                <div class="feature-item reveal">
                    <div class="feature-icon"><i class="fa-solid fa-id-card" aria-hidden="true"></i></div>
                    <h3 class="feature-title">ID検索</h3>
                    <p class="feature-desc">現場IDから素早く該当データへアクセス。</p>
                </div>
                <div class="feature-item reveal">
                    <div class="feature-icon"><i class="fa-solid fa-heading" aria-hidden="true"></i></div>
                    <h3 class="feature-title">件名</h3>
                    <p class="feature-desc">案件・件名単位での管理に対応。</p>
                </div>
                <div class="feature-item reveal">
                    <div class="feature-icon"><i class="fa-solid fa-building" aria-hidden="true"></i></div>
                    <h3 class="feature-title">現場名</h3>
                    <p class="feature-desc">現場名称でのフィルタ・検索が可能。</p>
                </div>
                <div class="feature-item reveal">
                    <div class="feature-icon"><i class="fa-solid fa-circle-plus" aria-hidden="true"></i></div>
                    <h3 class="feature-title">新規作成</h3>
                    <p class="feature-desc">直感的UIで新規日報を素早く登録。</p>
                </div>
                <div class="feature-item reveal">
                    <div class="feature-icon"><i class="fa-solid fa-qrcode" aria-hidden="true"></i></div>
                    <h3 class="feature-title">QRコード発行</h3>
                    <p class="feature-desc">QRから協力会社も簡単入力。</p>
                </div>
                <div class="feature-item reveal">
                    <a href="{{ route('admin.id') }}" class="feature-link" aria-label="管理画面へ">
                        <div class="feature-icon"><i class="fa-solid fa-ellipsis" aria-hidden="true"></i></div>
                        <h3 class="feature-title">その他</h3>
                    </a>
                    <p class="feature-desc">必要な拡張機能を順次追加予定。</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature List (cards) -->
    <section class="section section-alt" id="features-list">
        <div class="container">
            <h2 class="section-title reveal">現場業務を支える充実の機能</h2>
            <div class="cards" style="width:min(1120px,92%);margin:0 auto;">
                <article class="card reveal">
                    <div class="card-icon"><i class="fa-solid fa-diagram-project" aria-hidden="true"></i></div>
                    <h3 class="card-title">現場情報の一元管理</h3>
                    <p class="card-desc">案件・現場・担当者・進捗を一画面で俯瞰。CSVレポート出力に対応。</p>
                </article>
                <article class="card reveal">
                    <div class="card-icon"><i class="fa-solid fa-file-pen" aria-hidden="true"></i></div>
                    <h3 class="card-title">作業日報の記録</h3>
                    <p class="card-desc">ポチッと入力で素早く記録。写真添付や作業時間、自動集計に対応。</p>
                </article>
                <article class="card reveal">
                    <div class="card-icon"><i class="fa-solid fa-qrcode" aria-hidden="true"></i></div>
                    <h3 class="card-title">QR入力レポート</h3>
                    <p class="card-desc">協力会社向けにQRで入力案内。提出状況を可視化し、レポート作成。</p>
                </article>
                <article class="card reveal">
                    <div class="card-icon"><i class="fa-solid fa-user-clock" aria-hidden="true"></i></div>
                    <h3 class="card-title">勤怠・稼働時間</h3>
                    <p class="card-desc">開始/終了の打刻と休憩入力で日次稼働を自動計算。</p>
                </article>
                <article class="card reveal">
                    <div class="card-icon"><i class="fa-solid fa-paperclip" aria-hidden="true"></i></div>
                    <h3 class="card-title">写真・ファイル添付</h3>
                    <p class="card-desc">現場写真や図面を添付し、関係者と素早く共有。</p>
                </article>
                <article class="card reveal">
                    <div class="card-icon"><i class="fa-solid fa-user-shield" aria-hidden="true"></i></div>
                    <h3 class="card-title">権限・承認フロー</h3>
                    <p class="card-desc">ロールに応じた閲覧/編集権限と承認フローで統制。</p>
                </article>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section class="section" id="contact">
        <div class="container">
            <h2 class="section-title reveal">お問い合わせ（管理部へ連絡）</h2>
            <form class="contact-form reveal" action="#" method="post" novalidate>
                <div class="form-row">
                    <label for="name">お名前</label>
                    <input id="name" name="name" type="text" placeholder="山田 太郎" required />
                </div>
                <div class="form-row">
                    <label for="email">メールアドレス</label>
                    <input id="email" name="email" type="email" placeholder="example@company.jp" required />
                </div>
                <div class="form-row">
                    <label for="message">お問い合わせ内容</label>
                    <textarea id="message" name="message" rows="5" placeholder="導入に関するご相談やお見積りのご依頼など"></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">送信する</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Back to top -->
    <button id="backToTop" class="back-to-top" aria-label="ページトップへ">
        <i class="fa-solid fa-arrow-up"></i>
    </button>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="container footer-inner">
            <p class="copyright">&copy; <span id="year"></span> 作業日報管理システム【らくラクポチッと日報】</p>
            <ul class="footer-links">
                <li><a href="#features-intro">機能</a></li>
                <li><a href="#contact">お問い合わせ</a></li>
                <li><a href="#top">トップへ戻る</a></li>
            </ul>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('js/main.js') }}" defer></script>
</body>

</html>
