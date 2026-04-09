(function(){let o=document.querySelector(".check-all");o.addEventListener("click",l);function l(){o.checked?document.querySelectorAll(".invoice-checkbox input").forEach(function(e){e.closest(".invoice-list").classList.add("selected"),e.checked=!0}):document.querySelectorAll(".invoice-checkbox input").forEach(function(e){e.closest(".invoice-list").classList.remove("selected"),e.checked=!1})}flatpickr("#daterange",{mode:"range",dateFormat:"d-m-y"}),flatpickr(".invoice-date",{});let i=0;document.querySelector(".invoice-add-item").onclick=()=>{let e=`<tr class="invoice-list">
        <td class=""><input type="number" class="ti-form-input " placeholder="s.no"></td>
        <td class=""><input type="text" class="ti-form-input " placeholder="Product name"></td>
        <td class="font-semibold ">
            <div class="flex rounded-sm shadow-sm">
            <button type="button"
              class="quantity-minus inline-flex flex-shrink-0 justify-center items-center h-8 w-8 ltr:rounded-l-sm rtl:rounded-r-sm border border-transparent font-semibold bg-primary text-white hover:bg-primary focus:z-10 focus:outline-none focus:ring-2 focus:ring-primary transition-all text-sm">
              <i class="ti ti-minus"></i>
            </button>
            <input type="text" id="quantity-${i}" name="quantity"
              class="p-0 ti-form-input w-20 rounded-none shadow-sm focus:z-10 text-center"
               value=0 readonly>
            <button type="button"
              class="quantity-plus inline-flex flex-shrink-0 justify-center items-center h-8 w-8  ltr:rounded-r-sm rtl:rounded-l-sm border border-transparent font-semibold bg-primary text-white hover:bg-primary focus:z-10 focus:outline-none focus:ring-2 focus:ring-primary transition-all text-sm">
              <i class="ti ti-plus"></i>
            </button>
          </div>
        </td>
        <td class=""><input type="number" class="ti-form-input " placeholder="value"></td>
        <td class= " font-medium"><input type="number" class="ti-form-input " placeholder="Total amount"></td>
        <td class= " font-medium">
                                            <div class="hs-tooltip ti-main-tooltip">
                                                <a href="javascript:void(0);"
                                                    class="invoice-btn m-0 hs-tooltip-toggle w-8 h-8 ti-btn rounded-full p-0 transition-none focus:outline-none bg-danger/10 border-danger/10 text-danger hover:bg-danger/30 hover:border-danger hover:text-danger">
                                                    <i class="ti ti-trash"></i>
                                                    <span
                                                        class="hs-tooltip-content ti-main-tooltip-content py-1 px-2 bg-gray-900 text-xs font-medium text-white shadow-sm dark:bg-slate-700"
                                                        role="tooltip">
                                                        Delete
                                                    </span>
                                                </a>
                                            </div>
                                        </td>
        </tr>`;document.querySelector(".invoice-body").innerHTML+=e,i=i+1};var r=document.querySelectorAll(".quantity-minus"),c=document.querySelectorAll(".quantity-plus"),s=0,a=30;r.forEach(e=>{e.onclick=function(n){let t=n.currentTarget.nextElementSibling.value;t>s&&(t=t-1,n.currentTarget.nextElementSibling.value=t)}}),c.forEach(e=>{e.onclick=function(n){let t=n.currentTarget.previousElementSibling.value;t<a&&(t=Number(t)+1,n.currentTarget.previousElementSibling.value=t)}}),document.addEventListener("DOMContentLoaded",function(){var e=document.querySelectorAll("[data-trigger]");for(let t=0;t<e.length;++t){var n=e[t];new Choices(n,{allowHTML:!0,searchEnabled:!1,removeItemButton:!0})}}),document.querySelectorAll(".invoice-btn").forEach(e=>{e.onclick=()=>{e.closest(".invoice-list").remove()}})})();document.getElementById("invoice-create").onclick=()=>{document.querySelector(".invoice-title").innerHTML="Create Invoice"};
