<div class="navbar-default sidebar" role="navigation" style="overflow: visible;">

            <div class="slimScrollDiv" style="position: relative; overflow: visible; width: auto; height: 100%;"><div class="sidebar-nav slimscrollsidebar active" style="overflow-x: visible; overflow-y: hidden; width: auto; height: 100%;">
                <div class="sidebar-head"><h3><span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span> <span class="hide-menu">Navigation</span></h3> </div>
                <div class="user-profile">
                    <div class="dropdown user-pro-body">
                        <div><img src="<?php echo e(Auth::user()->logo ? Auth::user()->logo : '/images/user_default.png'); ?>" alt="user-img" class="img-circle"></div>
                        <p class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo e(Auth::user()->name); ?></p>
                        
                    </div>
                </div>
                <ul class="nav in" id="side-menu">
                    <?php $__currentLoopData = Auth::user()->menuLoad(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $node): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li> <a href="#" class="waves-effect"><i class="<?php echo e($node['setting']['i_class']); ?>" data-icon="v"></i> <span class="hide-menu"> <?php echo e($node['setting']['name']); ?> <span class="fa arrow"></span> </span></a>
                            <ul class="nav nav-second-level collapse" style="padding-left: 20px;">
                            <?php $__currentLoopData = $node['subnodeArr']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subnode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <li>
                                <li> <a href="<?php echo e($subnode['url']); ?>"><span class="hide-menu"><?php echo e($subnode['name']); ?></span></a> </li>
                              </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </ul>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        </div>
    </div>