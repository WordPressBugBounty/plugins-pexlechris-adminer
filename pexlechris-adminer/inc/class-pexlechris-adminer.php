<?php
class Pexlechris_Adminer extends Adminer\Adminer {

    public function get_wp_locale()
	{
		$wp_user_locale = get_user_locale();
        $expl = explode('_', $wp_user_locale);
		$adminer_locale = $expl[0];

		/**
         * Filter the locale of Adminer UI.
         *
		 * @since 3.1.0
         *
         * @param string $adminer_locale
		 */
        return apply_filters('pexlechris_adminer_locale', $adminer_locale);
	}

	function credentials() {
		// server, username and password for connecting to database
		return array(DB_HOST, DB_USER, DB_PASSWORD);
	}

	function login($login, $password) {
		return true; // login even if password is empty string
	}

    function loginForm(){
        ob_start();
        parent::loginForm();
        $form_html = ob_get_clean();
        $form_html = str_replace(
            "<table class='layout'>",
            "<table class='layout pexle_loginForm'>",
            $form_html
        );
        echo $form_html;
    }

    function head($Ib = null){
        $this->pexlechris_adminer_head();
		/**
		 * If a developer want to add just JS and/or CSS in head, he/she can just use the action pexlechris_adminer_head.
		 * See plugin's FAQs, for more.
		 */
		do_action('pexlechris_adminer_head');
		return true;
    }

	function pexlechris_adminer_head()
	{
		?>
		<script nonce="<?php echo esc_attr( Adminer\get_nonce() )?>">
            verifyVersion = function () {}; // Disable version checker

            // auto login
            window.addEventListener('load', function(){

                if ( null === document.querySelector('.pexle_loginForm') ) return;

                var wpLocale = '<?php echo $this->get_wp_locale(); ?>';

                var langExists = !!document.querySelector( '#lang option[value="' + wpLocale + '"]' );
                var selectElement = document.querySelector('#lang select');

                if( langExists && selectElement.value != wpLocale ){
                    selectElement.value = wpLocale;
                    var event = new Event('change', { bubbles: true });
                    selectElement.dispatchEvent(event);

                }else{
                    document.querySelector('.pexle_loginForm + p > input').click();
                }

            });
		</script>

		<style>
            /* UI Customizations - START */
            a,
            a:visited{
                color: #0051cc;
            }

            #tables a.select {
                font-size: 0;
                padding: 12px 13px 5px 13px;
                background-size: 16px;
                background-repeat: no-repeat;
                background-position: center 0;
                background-image: url('data:image/svg+xml;utf-8,<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-article" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z"></path><path d="M7 8h10"></path><path d="M7 12h10"></path><path d="M7 16h10"></path></svg>');
                margin-left: -8px;
            }

            #table thead tr td a[href$="&modify=1"] {
                font-size: 0;
                padding: 12px 13px 5px 13px;
                margin-left: -6px;
                background-size: 16px;
                background-repeat: no-repeat;
                background-position: center 0;
                background-image: url('data:image/svg+xml;utf-8,<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path><path d="M16 5l3 3"></path></svg>');
            }

            #table tbody tr td a.edit {
                font-size: 0;
                padding: 12px 11px 5px 11px;
                background-size: 16px;
                background-repeat: no-repeat;
                background-position: center 0;
                background-image: url('data:image/svg+xml;utf-8,<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"/><path d="M13.5 6.5l4 4"/></svg>');
            }
            #tables a.active + a{
                font-weight: bold;
            }
            /* UI Customizations - END */



            #lang,
            .pexle_loginForm *,
            .pexle_loginForm + p,
            #version,
            p.logout {
                display: none;
            }
            .pexle_loginForm::before {
                content: "<?php esc_html_e('You are connecting to the database...', 'pexlechris-adminer'); ?>";
            }

            .pexle_loginForm{
                border: unset;
            }

            #menu{
                margin-top: 0;
                top: 0
            }
            #menu > h1{
                border-top: 0;
            }

			<?php if( !defined('PEXLECHRIS_ADMINER_HAVE_ACCESS_ONLY_IN_WP_DB') || true === PEXLECHRIS_ADMINER_HAVE_ACCESS_ONLY_IN_WP_DB ): ?>
                #breadcrumb > a:nth-child(2){
                    width: 17px;
                    display: inline-block;
                    margin-left: -14px;
                    color: transparent;
                    background: #eee;
                    margin-right: -23px;
                    pointer-events: none;
                }
                #dbs{
                    display: none;
                }
                .footer > div > fieldset > div > p{
                    width: 150px;
                    color: transparent;
                    display: inline-block;
                    margin-top: -15px;
                }
                .footer > div > fieldset > div > p > *:not([name="copy"]){
                    display: none;
                }
                .footer > div > fieldset > div > p > [name="copy"]{
                    float: left;
                    margin-top: -0.5px;
                }
			<?php endif; ?>

		</style>
		<?php
	}
}