<?php
use mdm\admin\components\MenuHelper;

$callback = function($menu){
    $data = json_decode($menu['data'], true);
    $items = $menu['children'];

    if($menu['route'])
    {
        $url = [$menu['route'].'?' . $data['url_parameter']];
    }else{
        $url = '#';
    }
    $return = [
        'label' => $menu['name'],
        'url' => $url,
    ];
    //处理我们的配置
    if ($data) {
        //visible
        isset($data['visible']) && $return['visible'] = $data['visible'];
        //icon
        isset($data['icon']) && $data['icon'] && $return['icon'] = $data['icon'];
        //other attribute e.g. class...
        $return['options'] = $data;
    }
    //没配置图标的显示默认图标
    (!isset($return['icon']) || !$return['icon']) && $return['icon'] = 'fa fa-circle-o';
    $items && $return['items'] = $items;
    return $return;
};
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/images/new_user.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?=Yii::$app->user->getIdentity()->name;?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> 在线</a>
            </div>
        </div>

        <?= dmstr\widgets\Menu::widget([
            'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
            'items' => MenuHelper::getAssignedMenu(Yii::$app->user->id, null, $callback),
        ]); ?>

        
    </section>

</aside>
<!-- 左侧菜单高亮 jieson 2018.11.14-->
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script>
    $(function(){
        $('.sidebar-menu li:not(.treeview) > a').on('click', function(){
            var $parent = $(this).parent().addClass('active');
            $parent.siblings('.treeview.active').find('> a').trigger('click');
            $parent.siblings().removeClass('active').find('li').removeClass('active');
        });

        $(window).on('load', function(){
            $('.sidebar-menu a').each(function(){
                if(this.href === window.location.href){
                    $(this).parent().addClass('active')
                            .closest('.treeview-menu').addClass('.menu-open')
                            .closest('.treeview').addClass('active');
                }
            });
        });
    });
</script>


