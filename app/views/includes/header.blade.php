@section('header') 
<!--header-->
	<nav class="main-nav">
        <ul>
            <li><a href="" class="full-c">FULL CONCEPT</a></li>
            <li><a href="" class="experiencia">EXPERIENCIA</a></li>
            <li><a href="" class="vision">UNA GRAN VISI&Oacute;N</a></li>
            <li><a href="" class="contacto">CONTACTO</a></li>
        </ul>
    </nav>
    <section class="menu-content">
    <!--FULL CONCEPT-->
        <div class="full-concept">
            <img src="{{ asset('img/bread-crumb.png') }}" alt="!" class="bread-crumb">
            <p>FULL CONCEPT es una empresa que nace de la necesidad de brindar soluciones eficientes para proyectos inmobiliarios de gran alcance. Un equipo conformado por especialistas en el ramo y expertos en el mercado constructivo y de desarrollos en Mazatlán y el resto de la República.</p>
    
            <p>FULL CONCEPT posee un enfoque integral que parte de la identidad individual, la conceptualización, el diseño y el desarrollo de proyectos en los que aporta su particular visión estratégica y estética con el claro objetivo de darle valor a cada terreno confiado; así como guiar el proyecto con un alto sentido de vanguardia, innovación y una perspectiva fresca y diferente.</p>
                
            <p class="gen-x-btn center"><img src="{{ asset('img/x-btn.png') }}" alt="X"></p>
        </div>
    <!--FULL CONCEPTt-->
    
    <!--EXPERIENCIA-->
        <div class="exp">
            <img src="{{ asset('img/bread-crumb.png') }}" alt="!" class="bread-crumb">
            <p>Su asesoría y consultoría en desarrollo de nuevos negocios son claves para el correcto proceso e implementación de infraestructura, así como la acertada aplicación de evaluación y reestructura para empresas y su maximizaci&oacute;n de resultados en finanzas. Full Concept tiene amplia experiencia en evaluación de factibilidad para intensiones de utilidad, orientación hacia nuevas perspectivas de mercado y realización de planes para comercialización.</p>
            
            <p class="gen-x-btn center"><img src="{{ asset('img/x-btn.png') }}" alt="X"></p>
        </div>
    <!--EXPERIENCIA-->
    
    <!--UNA GRAN VISION-->
        <div class="gran-vis">
            <img src="{{ asset('img/bread-crumb.png') }}" alt="!" class="bread-crumb">
            <p>Aciertos de negocio, asesoría y consultoría en desarrollos, factibilidad y maximizaci&oacute;n de resultados.</p>
    
            <p>•Aportación de valor en los planes de desarrollo inmobiliario. </p>
            <p>•Potencialización de negocios en el competitivo mundo de la construcción.</p>
            <p>•Respaldo eficiente en cada paso de tu inversión inmobiliaria.</p>
    
                
            <p class="gen-x-btn center"><img src="{{ asset('img/x-btn.png') }}" alt="X"></p>
        </div>
    <!--UNA GRAN VISION-->
    
    <!--CONTACTO-->
        <div class="contact-cont">
            <img src="{{ asset('img/bread-crumb.png') }}" alt="!" class="bread-crumb">
            <p>Sea cual sea tu necesidad de negocio inmobiliario, comunícate con nosotros. Trabajando juntos tus terrenos tienen mayor potencial.</p>
    
            <div class="contact-ico center">
                <a href="mailto:info@fullconcept.mx"><img src="{{ asset('img/email-ico.png') }}" alt=""></a>
                <p>ENVIAR E-MAIL</p>
            </div>	
            
            <div class="contact-ico center">
                <a href="#" class="ver-mapa"><img src="{{ asset('img/ubicacion-ico.png') }}" alt=""></a>
                <p>VER MAPA</p>
            </div>	
            
            <div class="contact-ico center">
                <a href="mailto:?subject=Full Concept&body=info@FullConcept.mx"><img src="{{ asset('img/compartir-ico.png') }}" alt=""></a>
                <p>COMPARTIR CONTACTO</p>
            </div>	
            <p class="gen-x-btn center"><img src="{{ asset('img/x-btn.png') }}" alt="X"></p>
        </div>
    <!--CONTACTO-->
    
    </section>
<!--header-->
@show