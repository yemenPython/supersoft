 <button class="btn btn-info" type="button"
                onclick="show_transfer_print('{{route('admin:lockers-transfer.show' ,['id' => $item->id])}}')">
    <i class="fa fa-print"></i>
</button>
 @include('admin.partial.upload_library.btn_upload', ['id'=> $item->id])
