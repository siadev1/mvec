   <!-- End Header  -->
   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >

   @include('frontend.body.header')



    
    <!--End header-->
    <main class="main pages">
        @yield('user')
        
    </main>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        @if(Session::has('message'))
        var type = "{{ Session::get('alert-type','info') }}"
        switch(type){
           case 'info':
               toastr.info(" {{ Session::get('message') }} ");
           break;
           
           case 'success':
               toastr.success(" {{ Session::get('message') }} ");
               break;
               
               case 'warning':
           toastr.warning(" {{ Session::get('message') }} ");
           break;
           
           case 'error':
               toastr.error(" {{ Session::get('message') }} ");
               break; 
            }
            @endif 
            </script>
            @include('frontend.body.footer')