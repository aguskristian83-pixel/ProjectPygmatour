<%@ Page Title="Home" Language="C#" MasterPageFile="~/Site.Master" AutoEventWireup="true" CodeBehind="Pygmatour.aspx.cs" Inherits="PygmaPortfolio._Default" %>

<asp:Content ID="BodyContent" ContentPlaceHolderID="MainContent" runat="server">

    <style>
        .home-intro { padding: 0 0 64px; background: #f5f7f4; }
        .home-hero { position: relative; min-height: 560px; display: flex; align-items: center; overflow: hidden; background: #173454; }
        .home-hero::before { content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, rgba(9, 33, 52, .82) 0%, rgba(9, 33, 52, .58) 42%, rgba(9, 33, 52, .12) 100%), url('img/home/home.png') center/cover; }
        .home-hero .container { position: relative; z-index: 1; }
        .home-hero-copy { max-width: 620px; color: #fff; padding: 72px 0 110px; }
        .home-kicker { color: #f6c453; font-size: .75rem; font-weight: 700; letter-spacing: .16em; margin: 0 0 18px; text-transform: uppercase; }
        .home-hero h1 { color: #fff; font-size: clamp(2.6rem, 5vw, 4.8rem); font-weight: 600; line-height: 1.02; margin: 0 0 22px; max-width: 570px; }
        .home-hero-text { color: rgba(255, 255, 255, .88); font-size: 1.05rem; line-height: 1.7; margin: 0 0 30px; max-width: 500px; }
        .home-actions { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
        .home-action-primary, .home-action-secondary { display: inline-flex; align-items: center; justify-content: center; min-height: 46px; padding: 0 21px; border-radius: 3px; font-size: .86rem; font-weight: 600; text-decoration: none; }
        .home-action-primary { background: #f6c453; color: #173454; }
        .home-action-secondary { border: 1px solid rgba(255, 255, 255, .7); color: #fff; }
        .home-action-primary:hover, .home-action-secondary:hover { color: #173454; background: #fff; }
        .home-action-secondary:hover { color: #173454; }
        .home-stats { position: absolute; right: 0; bottom: 34px; display: flex; gap: 28px; color: #fff; }
        .home-stat strong { display: block; font-size: 1.45rem; font-weight: 600; }
        .home-stat span { color: rgba(255, 255, 255, .72); font-size: .72rem; }
        .background-copy { padding: 48px 0 0; color: #294769; line-height: 1.65; }
        .background-copy h2 { color: #102b4e; font-size: 1.5rem; font-weight: 600; margin: 0 0 2px; }
        .background-copy h3 { color: #d49b2c; font-size: .8rem; letter-spacing: .13em; font-weight: 700; margin: 0 0 16px; }
        .background-copy p { max-width: 780px; margin-bottom: 0; }
        .about-page { padding: 30px 0 52px; color: #173454; }
        .about-page > .container { max-width: 950px; }
        .about-page h1 { color: #102b4e; font-size: 1.5rem; font-weight: 500; margin-bottom: 38px; }
        .about-page h2 { color: #102b4e; font-size: 1.25rem; text-align: center; margin-bottom: 12px; }
        .about-page .about-description { max-width: 720px; margin: 0 auto 38px; text-align: center; font-size: .75rem; line-height: 1.55; }
        .about-page .about-description strong { font-style: italic; }
        .about-point { max-width: 720px; margin: 0 auto 14px; text-align: center; }
        .about-point h3 { color: #102b4e; font-size: 1rem; margin-bottom: 10px; }
        .about-point p { margin: 0; font-size: .75rem; line-height: 1.55; }

        .page-contact { padding: 18px 0 0; }
        .page-contact .container { max-width: 980px; }
        .page-contact-title { color: #122d4e; font-size: 2rem; font-weight: 500; margin: 0 0 36px; }
        .contact-center-block { max-width: 620px; margin: 0 auto 36px; text-align: center; }
        .contact-center-block .lead { font-size: 1.1rem; color: #1d3d5a; font-style: italic; margin: 0 0 22px; }
        .contact-center-block .address { color: #1d3d5a; line-height: 1.8; font-size: 0.98rem; }
        .contact-center-block .address strong { font-weight: 700; }
        .contact-center-block .address a { color: #1d3d5a; text-decoration: none; }
        .contact-form-wrap { max-width: 760px; margin: 0 auto; }
        .contact-form-wrap h3 { font-size: 1.1rem; color: #1d3d5a; margin: 0 0 12px; font-weight: 400; }
        .contact-form-wrap .meta { font-size: 0.8rem; color: #6c757d; margin: 0 0 14px; }
        .contact-form-wrap label { display: block; font-size: 0.8rem; color: #1d3d5a; margin: 0 0 8px; font-weight: 600; }
        .contact-form-wrap .form-control { border: 1px solid #bfc7d0; border-radius: 0; box-shadow: none; background: #f9f9f9; color: #1d3d5a; font-size: 0.95rem; }
        .contact-form-wrap textarea.form-control { min-height: 120px; }
        .contact-form-wrap .input-row { display: flex; gap: 14px; margin-bottom: 16px; }
        .contact-form-wrap .input-row .field { flex: 1; }
        .contact-form-wrap .checkbox-row { display: flex; align-items: center; margin: 12px 0 18px; font-size: 0.82rem; color: #53657b; }
        .contact-form-wrap .checkbox-row input { margin-right: 10px; width: 15px; height: 15px; }
        .btn-submit { background: #0d6efd; color: #fff; border: none; border-radius: 4px; padding: 11px 18px; font-size: 0.9rem; font-weight: 600; }
        .btn-submit:hover { opacity: 0.95; }

        .comment-thread { max-width: 980px; margin: 42px auto 0; }
        .comment-count { font-size: 1.05rem; color: #1b2d42; font-weight: 400; margin: 0 0 20px; }
        .comment-count strong { font-weight: 700; }
        .comment-list { border-top: 1px solid #dfe5eb; }
        .comment-item { display: flex; align-items: flex-start; gap: 16px; padding: 22px 0 18px; border-bottom: 1px solid #e7ebf0; }
        .comment-item-reply { margin-left: 44px; }
        .comment-avatar { width: 42px; height: 42px; border-radius: 50%; background: #dfe5ec; color: #6d7d90; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: 700; flex-shrink: 0; }
        .comment-content { flex: 1; }
        .comment-meta { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 8px; }
        .comment-author { color: #1b2d42; font-size: 1.15rem; font-weight: 600; }
        .comment-date { color: #77849a; font-size: 0.9rem; }
        .comment-reply-link { color: #0d6efd; font-size: 0.95rem; display: inline-block; margin-bottom: 10px; text-decoration: none; }
        .comment-body { color: #2d3f52; font-size: 1rem; line-height: 1.8; }
        .reply-box { display: flex; justify-content: space-between; align-items: center; margin: 20px 0 10px; color: #1d3d5a; }
        .reply-to-label { font-size: 1.05rem; color: #1d3d5a; font-weight: 600; }
        .cancel-reply { color: #0d6efd; text-decoration: none; font-size: 0.95rem; }

        .portfolio-shell { border-top: 1px solid #dfe3e8; padding: 40px 0 80px; }
        .portfolio-shell .container { max-width: 950px; }
        .portfolio-heading { color: #122d4e; font-size: 1.45rem; font-weight: 500; margin: 0 0 16px; }
        .portfolio-shortcode { color: #173454; font-size: .72rem; line-height: 1.7; margin: 0; word-break: break-word; }
        .portfolio-2025 { padding-top: 0; }
        .portfolio-2025-image { display: block; width: min(100%, 478px); height: auto; margin: 0 auto; }

        @media (max-width: 767px) {
            .page-contact-title { font-size: 1.6rem; }
            .contact-center-block .lead { font-size: 0.95rem; }
            .contact-form-wrap .input-row { display: block; }
            .contact-form-wrap .input-row .field { margin-bottom: 14px; }
            .comment-meta { display: block; }
            .comment-date { display: block; margin-top: 6px; }
            .reply-box { display: block; }
            .portfolio-shell { padding-top: 30px; }
            .home-hero { min-height: 620px; }
            .home-hero-copy { padding: 68px 0 150px; }
            .home-hero h1 { font-size: 2.75rem; }
            .home-stats { right: auto; left: 0; bottom: 34px; gap: 20px; }
            .home-stat strong { font-size: 1.2rem; }
        }
    </style>

    <!-- Home -->
    <section class="home-intro">
        <div class="home-hero">
            <div class="container">
                <div class="home-hero-copy">
                    <p class="home-kicker">Incentive Tour Specialist</p>
                    <h1>Make every journey worth remembering.</h1>
                    <p class="home-hero-text">Thoughtful travel experiences for teams, companies, and curious explorers, planned with local insight and delivered with care.</p>
                    <div class="home-actions">
                        <a class="home-action-primary" href="#portfolio">Explore our journeys</a>
                        <a class="home-action-secondary" href="#contact">Plan a trip</a>
                    </div>
                </div>
                <div class="home-stats" aria-label="Pygmatour highlights">
                    <div class="home-stat"><strong>2012</strong><span>Established</span></div>
                    <div class="home-stat"><strong>30+</strong><span>Destinations</span></div>
                    <div class="home-stat"><strong>360°</strong><span>Travel support</span></div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="background-copy">
                <h2>Our Background</h2>
                <h3>PYGMATOUR</h3>
                <p>
                    (founded in October 2012) is an Incentive Tour Operating Company, offering a dedicated personalized service with depth experience in planning worldwide incentive trip<br />
                    and having vast knowledge and experience.<br />
                    Since 2017, we also do Travel Consultant for Corporate and Individual needs such as flight tickets, hotels booking, document handler (e.g. passport, visa) and other travel<br />
                    needs, with our experienced team and competitive fee rate.
                </p>
            </div>
        </div>
    </section>

    <!-- About Us -->
    <section id="about" class="about-page">
        <div class="container">
            <h1>About Us</h1>
            <h2>Incentive Tour Specialist in Indonesia</h2>
            <p class="about-description">
                <strong>PYGMATOUR</strong> (founded in October 2012) is an incentive tour operating company, offering a dedicated personalized service with depth experience in planning worldwide incentive trip and having vast knowledge and experience. We offer and organize groups such as company trip with team building, thematic gala dinner, students study tour, sport events, medical events, etc.
            </p>

            <div class="about-point">
                <h3>VISION</h3>
                <p>To become the leading incentive tour operator and our client's best partner, always providing the best possible product with the highest quality of services.</p>
            </div>

            <div class="about-point">
                <h3>MISSION</h3>
                <p>To provide every client with the best product and service possible, combined with professionalism and integrity. To serve as an extension of our client's management resources to ensure a comprehensive understanding of their goals and objectives.</p>
            </div>
        </div>
    </section>

    <!-- Services / Why choose us -->
    <section id="clients" class="section-wrap bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-tag">Why Choose Us</div>
                <h2 class="section-title">Tailored Experiences That Deliver Impact</h2>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-route"></i></div>
                        <h3>Customized Planning</h3>
                        <p>Every itinerary is crafted to match your goals, brand values, and audience preferences with precision.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-users"></i></div>
                        <h3>Team Coordination</h3>
                        <p>From concept to execution, we manage logistics, schedules, and stakeholder needs with care.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="fa-solid fa-medal"></i></div>
                        <h3>Premium Service</h3>
                        <p>We deliver memorable and premium experiences that strengthen relationships and create lasting impact.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="portfolio-shell">
        <div class="container">
            <h1 class="portfolio-heading" id="tour-2013">Tour 2013</h1>
            <p class="portfolio-shortcode">
                [fullwidth background_color="" background_image="http://www.pygmatour.com/wp-content/uploads/2014/10/bg.png"
                background_parallax="none" enable_mobile="no" parallax_speed="0.3" background_repeat="repeat"
                background_position="left top" video_url="" video_aspect_ratio="16:9" video_webm="" video_mp4=""
                video_ogg="" video_preview_image="" overlay_opacity="0" overlay_pattern="none" video_fade="no"
                video_border_size="0px" video_border_color="" border_style="solid" padding_top="0px"
                padding_bottom="0px" padding_left="0px" padding_right="0px" hundred_percent="yes"
                equal_height_columns="no" el_class="" el_id="" recent_works="no" recent_works_layout="one"
                recent_works_order="orderby" filters="no" columns="3" column_spacing="0" cat_slug="2013"
                exclude_cats="" number_posts="100" offset="" excerpt_length="35" strip_html="yes"
                carousel_layout="title_on_rollover" scroll_items="" autoplay="no" show_nav="yes"
                mouse_scrolling="no" animation_type="0" animation_direction="down" animation_speed="0.1"
                class="" /]
            </p>
        </div>
    </section>

    <section id="tour-2025" class="portfolio-shell portfolio-2025">
        <div class="container">
            <h1 class="portfolio-heading">Tour 2025</h1>
            <img src="img/portfolio/tour-2025.png" class="portfolio-2025-image" alt="Pygmatour Tour 2025 in Australia" />
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="page-contact">
        <div class="container">
            <h1 class="page-contact-title">Contact Us</h1>

            <div class="contact-center-block">
                <p class="lead">We'd love to Meet You In Person Or Via The Web!</p>

                <div class="address">
                    <strong>PT. ENAM DUNIA WISATA</strong><br />
                    Main Office: Komp. Puri Delta Mas Blok C-31<br />
                    Jl. Bandung Selatan No.43 - Jakarta Utara 14450, Indonesia<br />
                    Phone: +62 21 6667 0688<br />
                    Fax: +62 21 6667 0611<br />
                    Email: <a href="mailto:info@pygmatour.com">info@pygmatour.com</a>
                </div>
            </div>

            <div class="contact-form-wrap">
                <h3>Leave a Reply</h3>
                <p class="meta">Your email address will not be published. Required fields are marked *</p>

                <div class="mb-3">
                    <label>Comment *</label>
                    <asp:TextBox ID="txtMessage" runat="server" TextMode="MultiLine" Rows="6" CssClass="form-control" />
                </div>

                <div class="input-row">
                    <div class="field">
                        <label>Name *</label>
                        <asp:TextBox ID="txtName" runat="server" CssClass="form-control" placeholder="" />
                    </div>
                    <div class="field">
                        <label>Email *</label>
                        <asp:TextBox ID="txtEmail" runat="server" CssClass="form-control" TextMode="Email" placeholder="" />
                    </div>
                    <div class="field">
                        <label>Website</label>
                        <asp:TextBox ID="txtWebsite" runat="server" CssClass="form-control" placeholder="" />
                    </div>
                </div>

                <div class="reply-box">
                    <asp:Literal ID="litReplyTo" runat="server"></asp:Literal>
                    <asp:Literal ID="litCancelReply" runat="server"></asp:Literal>
                </div>

                <div class="checkbox-row">
                    <input class="form-check-input" type="checkbox" id="saveInfo" />
                    <label for="saveInfo">Save my name, email, and website in this browser for the next time I comment.</label>
                </div>

                <asp:Button ID="btnSubmit" runat="server" CssClass="btn btn-submit" Text="Post Comment" OnClick="btnSubmit_Click" />
                <asp:Label ID="lblStatus" runat="server" CssClass="text-success d-block mt-3"></asp:Label>

                <asp:Panel ID="pnlRecentComments" runat="server" CssClass="comment-thread" Visible="true">
                    <div class="comment-count">
                        <asp:Literal ID="litCommentCount" runat="server"></asp:Literal>
                    </div>
                    <div class="comment-list">
                        <asp:Literal ID="litComments" runat="server"></asp:Literal>
                    </div>
                </asp:Panel>
            </div>
        </div>
    </section>

</asp:Content>
