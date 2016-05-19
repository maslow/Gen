Dashboard Module for Gen
========================

Dashboard是后台管理中心模块，集成了管理员、管理控制台，是Gen的核心模块。

Dashboard通过读取每个模块的`导出导航菜单`与`导出权限`配置来完成对所有模块的集中式管理。

INTRODUCTION
============

Specifications
--------------
    @ ID : dashboard
    @ Bootstrap : Y
    @ Required : Y
    @ Migration : Y

Navigation
----------
    * Administrator  
        > Administrator List  
        > Create Administrator  
        > Reset Password
    * Role
        > Role List
        > Create Role

Permissions
-----------
    *  administrator.create          > Create Administrator
    *  administrator.list            > Browser Administrators
    *  administrator.update          > Update Administrator
    *  administrator.delete          > Delete Administrator
    *  administrator.reset-password  > Reset Password
    
    *  role.create                   > Create Role
    *  role.list                     > Browser Roles
    *  role.update                   > Update Role
    *  role.delete                   > Delete Role
    
Dependencies
------------
    *
    
Installation
------------
    1. Copy the module to @app/runtime/module_transfer_station
    
    2. Run the command:
        ```
            php yii module/install dashboard
        ```
