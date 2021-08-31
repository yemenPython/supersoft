<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">{{ __('branch products') }} - [{{ $item->name }}] [{{count($item->products)}}]</h4>
</div>
<div class="modal-body">
    <div class="row">
        <form method="post" action="{{route('admin:banks.bank_data.assign_products')}}" id="productsForm">
            @csrf
            <input type="hidden" name="bank_data_id" value="{{$item->id}}">
            <div class="col-md-12 text-center">
                <table id="productsTable" class="remove-items table table-responsive">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>{{__('Product Name')}}</th>
                        <th>
                            <div class="checkbox danger">
                                <input onclick="checkAllProducts(event)" type="checkbox" id="selectAllProducts">
                                <label for="selectAllProducts">{{__('Select All')}}</label>
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody id="productList">
                    @foreach($items as $index=>$product)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$product->name}}</td>
                            <td>
                                <div class="checkbox danger">
                                    <input type="checkbox" id="checkbox-products-{{$product->id}}"
                                           {{in_array($product->id, $item->products->pluck('id')->toArray())
                                              ? 'checked' : ''}}  name="products[]" value="{{$product->id}}">
                                    <label for="checkbox-products-{{$product->id}}"></label>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>
<div class="modal-footer">
    <button form="productsForm" type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn">
        <i class="ico ico-left fa fa-save"></i>
        {{__('Save')}}
    </button>
    <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('Close')}}</button>
</div>

