Dashboard Module for Gen
========================

Dashboard是后台管理中心模块，集成了管理员、管理控制台，是Gen的核心模块。

Dashboard通过读取每个模块的`导出导航菜单`与`导出权限`配置来完成对所有模块的集中式管理。

说明
====

基础信息
-------
    @ 标识 : dashboard
    @ 全局引导 : 是
    @ 必要模块 : 是
    @ 数据迁移 : 是

导出菜单
-------
    * 管理员控制  
        > 管理员列表  
        > 新建管理员  
        > 修改密码

导出权限
-------
    *  dashboard.administrator.create          > 创建管理员
    *  dashboard.administrator.list            > 浏览管理员
    *  dashboard.administrator.update          > 更新管理员
    *  dashboard.administrator.delete          > 删除管理员
    *  dashboard.administrator.reset.password  > 修改自身密码
    
依赖模块
-------
    *
    

安装
---
    1. 将模块目录拷贝到@app/modules目录下
    
    2. 在项目根目录运行指令
        ```
            php yii module/update
        ```
