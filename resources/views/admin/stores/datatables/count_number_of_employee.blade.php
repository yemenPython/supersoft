<span class="label label-success m-5 ">
    <a style="color: white !important;font-size: 14px" target="_blank"
       href="{{route('admin:store_employee_history.index', ['store' => $store->id])}}">
        {{count($store->storeEmployeeHistories->where('status', 1))}}
    </a>
</span>
