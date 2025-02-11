@php
    $sideBarLinks = json_decode($sidenav);
@endphp

<div class="sidebar bg--dark" id="sidebar">
     <button class="toggle-btn" id="toggle-btn">&#8592;</button>
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{ route('admin.dashboard') }}" class="sidebar__main-logo"><img src="{{ siteLogo() }}"
                    alt="image"></a>
        </div>
        <div class="sidebar__menu-wrapper">
            <ul class="sidebar__menu">
                @foreach ($sideBarLinks as $key => $data)
                    @if (@$data->header && auth()->guard('admin')->id() == 1)
                        <li class="sidebar__menu-header">{{ __($data->header) }}</li>
                    @endif

                    @if (@$data->submenu)
                        @can(array_column($data->submenu, 'route_name'))
                            <li class="sidebar-menu-item sidebar-dropdown">
                                <a href="javascript:void(0)" class="{{ menuActive(@$data->menu_active, 3) }}">
                                    <i class="menu-icon {{ @$data->icon }}"></i>
                                    <span class="menu-title">{{ __(@$data->title) }}</span>
                                    @foreach (@$data->counters ?? [] as $counter)
                                        @if ($counter > 0)
                                            <span class="menu-badge menu-badge-level-one bg--warning ms-auto">
                                                <i class="fas fa-exclamation"></i>
                                            </span>
                                        @break
                                    @endif
                                @endforeach
                            </a>
                            <div class="sidebar-submenu {{ menuActive(@$data->menu_active, 2) }} ">
                                <ul>
                                    @foreach ($data->submenu as $menu)
                                        @php
                                                $submenuParams = null;
                                            if (@$menu->params) {
                                                foreach ($menu->params as $submenuParamVal) {
                                                    $submenuParams[] = array_values((array) $submenuParamVal)[0];
                                                }
                                            }
                                        @endphp

                                        @can($menu->route_name)
                                            <li class="sidebar-menu-item {{ menuActive(@$menu->menu_active) }} "
                                                data-route="{{ $menu->route_name }}">
                                                
                                                <a href="{{ route(@$menu->route_name, $submenuParams) }}" class="nav-link">
                                                    <i class="menu-icon las la-dot-circle"></i>
                                                    <span class="menu-title">{{ __($menu->title) }}</span>
                                                    @php $counter = @$menu->counter; @endphp
                                                    @if (@$$counter)
                                                        <span
                                                            class="menu-badge bg--info ms-auto">{{ @$$counter }}</span>
                                                    @endif
                                                </a>
                                            </li>
                                        @endcan
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @endcan
                @else
                    @php
                        $mainParams = null;
                        if (@$data->params) {
                            foreach ($data->params as $paramVal) {
                                $mainParams[] = array_values((array) $paramVal)[0];
                            }
                        }
                    @endphp
                    @can(@$data->route_name)
                        <li class="sidebar-menu-item {{ menuActive(@$data->menu_active) }}">
                            <a href="{{ route(@$data->route_name, $mainParams) }}" class="nav-link">
                                <i class="menu-icon {{ $data->icon }}"></i>
                                <span class="menu-title">{{ __(@$data->title) }}</span>
                                @php $counter = @$data->counter; @endphp
                                @if (@$$counter)
                                    <span class="menu-badge bg--info ms-auto">{{ @$$counter }}</span>
                                @endif
                            </a>
                        </li>
                    @endcan
                @endif
            @endforeach
        </ul>
    </div>
    <div class="version-info text-center text-uppercase">
        {{-- <span class="text--primary">{{ __(systemDetails()['name']) }}</span> --}}
        {{-- <span class="text--success">@lang('V'){{ systemDetails()['version'] }} </span> --}}
    </div>
</div>
</div>
<style type="text/css">
    .sidebar .toggle-btn {
  position: absolute;
  right: 0px; /* Vị trí của mũi tên */
  font-size: 30px;
  background: none;
  border: none;
  color: white;
  cursor: pointer;
  z-index: 999999;
}
.navbar-wrapper.shifted {
  margin-left: 0 !important; /* Di chuyển nội dung chính khi sidebar bị ẩn */
}
.body-wrapper.shifted {
  margin-left: 0 !important; /* Di chuyển nội dung chính khi sidebar bị ẩn */
}
.sidebar.closed {
  transform: translateX(-250px); /* Ẩn sidebar bằng cách dịch chuyển nó sang trái */
}

</style>

@push('script')
<script>
    let activeDataIds = [];

    // Lấy mảng activeDataIds từ Local Storage khi trang được tải lại
    if (localStorage.getItem('activeDataIds')) {
        activeDataIds = JSON.parse(localStorage.getItem('activeDataIds'));
        // console.log(activeDataIds); // In ra các activeDataId đã được lưu
    }
 
    $('li').each(function() {
        if ($(this).hasClass('active')) {
            const activeDataId = $(this).find('span').text(); // Lấy nội dung text trong thẻ span
            const activeDataValue = $(this).data('route');

            // Kiểm tra xem cặp activeDataValue và activeDataId đã tồn tại trong mảng activeDataIds hay không
            const existingIndex = activeDataIds.findIndex(item => Object.keys(item)[0] === activeDataValue &&
                item[activeDataValue] === activeDataId);
            
            if (existingIndex === -1) {
                activeDataIds.push({
                    [activeDataValue]: activeDataId
                });
            }
            localStorage.setItem('activeDataIds', JSON.stringify(activeDataIds));
            $('.sidebar__menu-wrapper').animate({
                scrollTop: eval($(this).offset().top - 320)
            }, 500);
            $('.navbar__action-list').css('display','none');
            $('.navbar-wrapper').css('padding','0px 30px 20px 30px');
        }
       
    });
       
</script>

@endpush