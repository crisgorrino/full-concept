@section('scripts')
	<script src="{{ asset('js/modernizr.custom.97074.js') }}"></script>
	<script src="{{ asset('js/jquery.js') }}" type="text/javascript"></script>
	<script src="{{ asset('js/script.js') }}" type="text/javascript"></script>
	<script src="{{ asset('js/jquery.lightbox_me.js') }}" type="text/javascript"></script>
    
    
    <div class="mapa" style="display:none;">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15139.806232592404!2d-103.5290529243117!3d18.4405084543862!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x843a827cc3a0d353%3A0xfcd8425d202c021e!2sLa+Ticla!5e0!3m2!1sen!2s!4v1403041725971" width="600" height="450" frameborder="0" style="border:0"></iframe>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
             $('.ver-mapa').click(function(e) {
                 $('.mapa').lightbox_me({
                     centered: true, 
                     onLoad: function() { 
                         $('#sign_up').find('input:first').focus()
                    }
                    });
                    e.preventDefault();
                });
         }); 
    </script>
@show