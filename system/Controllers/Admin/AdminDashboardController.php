<?php

namespace system\Controllers\Admin;

class AdminDashboardController extends AdminController
{
    public function dashboard (): void {
        echo $this->template->renderizar('dashboard.html', []);
    }
}