@section('scripts')
	<script src="{{ asset('js/modernizr.custom.97074.js') }}"></script>
	<script src="{{ asset('js/jquery.js') }}" type="text/javascript"></script>
	<script src="{{ asset('js/script.js') }}" type="text/javascript"></script>
	<script src="{{ asset('js/jquery.lightbox_me.js') }}" type="text/javascript"></script>
    
    
    <div class="mapa" style="display:none;">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3665.9575487398015!2d-106.4523579!3d23.244631799999997!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x869f54bb56027bfd%3A0xb03f282b73d3571b!2sAv+Camar%C3%B3n+S%C3%A1balo+333%2C+Las+Gaviotas%2C+82110+Mazatl%C3%A1n%2C+SIN!5e0!3m2!1sen!2smx!4v1407429691677" width="600" height="450" frameborder="0" style="border:0"></iframe>
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