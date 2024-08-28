<form class="formulario" name="frmImportarPCG" id="frmImportarPCG">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group" >
                <label>Adjuntar Excel<span class="text-red">*</span></label>
                <div class="input-group">
                    <input type="text" class="form-control" readonly>
                    <label class="input-group-btn">
                        <span class="btn btn-primary" onchange="return fileValidation()">
                            <i class="fa fa-folder-open-o"></i> Seleccionar&hellip; <input type="file" style="display: none;" id="filePCG" >
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div align="center">
        <button onclick="ImportarData()" type="button" class="btn bg-olive"><li class="fa fa-file-excel-o"></li> Importar</button>
        <button type="button" onclick="CloseModal('divmodal1');" class="btn btn-danger"><li class="fa fa-close"></li> Cancelar</button>
    </div>
</form>

<script>
    
    function fileValidation(){
        var fileInput = document.getElementById('filePCG');
        var file = fileInput.files[0];
        var fileName = file.name;
        var fileSize = file.size;
        var ext = fileName.split('.').pop();
        var fileSizeKb = fileSize/1024;
        //console.log(fileSizeKb);
        switch (ext) {
            case 'xlsx':
            case 'xls':
            break;
            default:
            alert('El archivo no tiene la extensión adecuada');
                fileInput.value = '';
        }
    }

    function ImportarData() {
        var inputFileImage = document.getElementById("filePCG");
        var file = inputFileImage.files[0];
        var data = new FormData();
        
        data.append('file', file);
        data.append('accion' , 'IMPORTAR_DATA_EXCEL');

        $.ajax({
            method: "POST",
            url: 'controlador/contCategoria.php',
            data: data,
            contentType: false,
            processData: false,
            cache: false
        }).done(function(text){
            alert(text);
            if(text.substring(0,3)!="***"){
                verListado();
                CloseModal("divmodal1");
            }
        });
    }


    $(function() {
        $(document).on('change', ':file', function() {
            var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        });

        $(document).ready( function() {
            $(':file').on('fileselect', function(event, numFiles, label) {

                var input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;

                if( input.length ) {
                    input.val(log);
                }else {
                    if( log ) alert(log);
                }
            });
        }); 
    });

</script>