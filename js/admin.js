jQuery( document ).ready( function( $ ) {

        // Uploading files
        var fileFrame = null; 
        var imageWrap = $( '#cptwp-media-img-container' );
        var imageInput = $( '#cptwp-media-img' );

        $( '#cptwp-media-upload' ).on( 'click', function( event ){
            event.preventDefault();
            var $this= $( this ); 
            if ( fileFrame ) {
			fileFrame.open();
			return;
		}		
            fileFrame = wp.media.frames.fileFrame = wp.media( {
                    title    : $this.attr( 'data-title' ),
                    button   : {
                        text : $this.attr( 'data-button' ),
                    },
                    library: {
                        type: 'image'
                    },
                    multiple : false  
            } );
            fileFrame.on( 'select', function(){
                var attachment = fileFrame.state().get( 'selection' ).first().toJSON();
                imageInput.val(attachment.id);
                imageWrap.html( '<img src="' + attachment.url + '" style="max-width:100%;" />' );                
            });
            
            fileFrame.open();    
        });        
        
});