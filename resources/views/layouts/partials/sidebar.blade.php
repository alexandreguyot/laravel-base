{{-- Sidebar DaisyUI — layout gauche --}}
<aside class="bg-base-100 w-64 min-h-screen flex flex-col border-r border-base-200">

    {{-- Logo --}}
    <div class="px-4 py-5 border-b border-base-200">
        <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-lg text-primary">
            <x-icon name="layout-dashboard" class="w-6 h-6" />
            {{ trans('panel.site_title') }}
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-2">
        <ul class="menu menu-sm w-full gap-0.5">

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('home') }}"
                    class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <x-icon name="layout-dashboard" class="w-4 h-4" />
                    {{ trans('global.dashboard') }}
                </a>
            </li>

            {{-- Gestion des utilisateurs --}}
            @feature('user_management')
            @can('user_management_access')
            <li class="menu-title mt-3 text-[10px] uppercase tracking-widest text-base-content/40 px-2">
                Administration
            </li>
            <li>
                <details {{ request()->is('*/permissions*','*/roles*','*/users*','*/audit-logs*') ? 'open' : '' }}>
                    <summary>
                        <x-icon name="users" class="w-4 h-4" />
                        {{ trans('cruds.userManagement.title') }}
                    </summary>
                    <ul>
                        @can('permission_access')
                        <li><a href="{{ route('permissions.index') }}"
                            class="{{ request()->is('*/permissions*') ? 'active' : '' }}">
                            <x-icon name="key" class="w-4 h-4" />
                            {{ trans('cruds.permission.title') }}
                        </a></li>
                        @endcan
                        @can('role_access')
                        <li><a href="{{ route('roles.index') }}"
                            class="{{ request()->is('*/roles*') ? 'active' : '' }}">
                            <x-icon name="briefcase" class="w-4 h-4" />
                            {{ trans('cruds.role.title') }}
                        </a></li>
                        @endcan
                        @can('user_access')
                        <li><a href="{{ route('users.index') }}"
                            class="{{ request()->is('*/users*') ? 'active' : '' }}">
                            <x-icon name="user" class="w-4 h-4" />
                            {{ trans('cruds.user.title') }}
                        </a></li>
                        @endcan
                        @feature('audit_logs')
                        @can('audit_log_access')
                        <li><a href="{{ route('audit-logs.index') }}"
                            class="{{ request()->is('*/audit-logs*') ? 'active' : '' }}">
                            <x-icon name="file-text" class="w-4 h-4" />
                            {{ trans('cruds.auditLog.title') }}
                        </a></li>
                        @endcan
                        @endfeature
                    </ul>
                </details>
            </li>
            @endcan
            @endfeature

            {{-- Alertes --}}
            @feature('user_alerts')
            @can('user_alert_access')
            <li>
                <a href="{{ route('user-alerts.index') }}"
                    class="{{ request()->is('*/user-alerts*') ? 'active' : '' }}">
                    <x-icon name="bell" class="w-4 h-4" />
                    {{ trans('cruds.userAlert.title') }}
                </a>
            </li>
            @endcan
            @endfeature

            {{-- Produits --}}
            @feature('product_management')
            @can('product_management_access')
            <li class="menu-title mt-3 text-[10px] uppercase tracking-widest text-base-content/40 px-2">
                E-commerce
            </li>
            <li>
                <details {{ request()->is('*/product*') ? 'open' : '' }}>
                    <summary>
                        <x-icon name="shopping-cart" class="w-4 h-4" />
                        {{ trans('cruds.productManagement.title') }}
                    </summary>
                    <ul>
                        @can('product_category_access')
                        <li><a href="{{ route('product-categories.index') }}"
                            class="{{ request()->is('*/product-categories*') ? 'active' : '' }}">
                            <x-icon name="folder" class="w-4 h-4" />
                            {{ trans('cruds.productCategory.title') }}
                        </a></li>
                        @endcan
                        @can('product_tag_access')
                        <li><a href="{{ route('product-tags.index') }}"
                            class="{{ request()->is('*/product-tags*') ? 'active' : '' }}">
                            <x-icon name="tag" class="w-4 h-4" />
                            {{ trans('cruds.productTag.title') }}
                        </a></li>
                        @endcan
                        @can('product_access')
                        <li><a href="{{ route('products.index') }}"
                            class="{{ request()->is('*/products*') ? 'active' : '' }}">
                            <x-icon name="package" class="w-4 h-4" />
                            {{ trans('cruds.product.title') }}
                        </a></li>
                        @endcan
                    </ul>
                </details>
            </li>
            @endcan
            @endfeature

            {{-- Calendrier système --}}
            @feature('system_calendar')
            @can('system_calendar_access')
            <li>
                <a href="{{ route('system-calendars.index') }}"
                    class="{{ request()->is('*/system-calendars*') ? 'active' : '' }}">
                    <x-icon name="calendar" class="w-4 h-4" />
                    {{ trans('cruds.systemCalendar.title') }}
                </a>
            </li>
            @endcan
            @endfeature

            {{-- Contenu --}}
            @feature('content_management')
            @can('content_management_access')
            <li class="menu-title mt-3 text-[10px] uppercase tracking-widest text-base-content/40 px-2">
                Contenu
            </li>
            <li>
                <details {{ request()->is('*/content-*') ? 'open' : '' }}>
                    <summary>
                        <x-icon name="book-2" class="w-4 h-4" />
                        {{ trans('cruds.contentManagement.title') }}
                    </summary>
                    <ul>
                        @can('content_category_access')
                        <li><a href="{{ route('content-categories.index') }}"
                            class="{{ request()->is('*/content-categories*') ? 'active' : '' }}">
                            <x-icon name="folder" class="w-4 h-4" />
                            {{ trans('cruds.contentCategory.title') }}
                        </a></li>
                        @endcan
                        @can('content_tag_access')
                        <li><a href="{{ route('content-tags.index') }}"
                            class="{{ request()->is('*/content-tags*') ? 'active' : '' }}">
                            <x-icon name="tag" class="w-4 h-4" />
                            {{ trans('cruds.contentTag.title') }}
                        </a></li>
                        @endcan
                        @can('content_page_access')
                        <li><a href="{{ route('content-pages.index') }}"
                            class="{{ request()->is('*/content-pages*') ? 'active' : '' }}">
                            <x-icon name="file" class="w-4 h-4" />
                            {{ trans('cruds.contentPage.title') }}
                        </a></li>
                        @endcan
                    </ul>
                </details>
            </li>
            @endcan
            @endfeature

            {{-- CRM --}}
            @feature('crm')
            @can('basic_c_r_m_access')
            <li class="menu-title mt-3 text-[10px] uppercase tracking-widest text-base-content/40 px-2">
                CRM
            </li>
            <li>
                <details {{ request()->is('*/crm-*') ? 'open' : '' }}>
                    <summary>
                        <x-icon name="briefcase" class="w-4 h-4" />
                        {{ trans('cruds.basicCRM.title') }}
                    </summary>
                    <ul>
                        @can('crm_status_access')
                        <li><a href="{{ route('crm-statuses.index') }}"
                            class="{{ request()->is('*/crm-statuses*') ? 'active' : '' }}">
                            <x-icon name="circle-dot" class="w-4 h-4" />
                            {{ trans('cruds.crmStatus.title') }}
                        </a></li>
                        @endcan
                        @can('crm_customer_access')
                        <li><a href="{{ route('crm-customers.index') }}"
                            class="{{ request()->is('*/crm-customers*') ? 'active' : '' }}">
                            <x-icon name="user-plus" class="w-4 h-4" />
                            {{ trans('cruds.crmCustomer.title') }}
                        </a></li>
                        @endcan
                        @can('crm_note_access')
                        <li><a href="{{ route('crm-notes.index') }}"
                            class="{{ request()->is('*/crm-notes*') ? 'active' : '' }}">
                            <x-icon name="notes" class="w-4 h-4" />
                            {{ trans('cruds.crmNote.title') }}
                        </a></li>
                        @endcan
                        @feature('crm_documents')
                        @can('crm_document_access')
                        <li><a href="{{ route('crm-documents.index') }}"
                            class="{{ request()->is('*/crm-documents*') ? 'active' : '' }}">
                            <x-icon name="paperclip" class="w-4 h-4" />
                            {{ trans('cruds.crmDocument.title') }}
                        </a></li>
                        @endcan
                        @endfeature
                    </ul>
                </details>
            </li>
            @endcan
            @endfeature

            {{-- Tâches --}}
            @feature('task_management')
            @can('task_management_access')
            <li class="menu-title mt-3 text-[10px] uppercase tracking-widest text-base-content/40 px-2">
                Productivité
            </li>
            <li>
                <details {{ request()->is('*/task*') ? 'open' : '' }}>
                    <summary>
                        <x-icon name="list-check" class="w-4 h-4" />
                        {{ trans('cruds.taskManagement.title') }}
                    </summary>
                    <ul>
                        @can('task_status_access')
                        <li><a href="{{ route('task-statuses.index') }}"
                            class="{{ request()->is('*/task-statuses*') ? 'active' : '' }}">
                            <x-icon name="circle-dot" class="w-4 h-4" />
                            {{ trans('cruds.taskStatus.title') }}
                        </a></li>
                        @endcan
                        @can('task_tag_access')
                        <li><a href="{{ route('task-tags.index') }}"
                            class="{{ request()->is('*/task-tags*') ? 'active' : '' }}">
                            <x-icon name="tag" class="w-4 h-4" />
                            {{ trans('cruds.taskTag.title') }}
                        </a></li>
                        @endcan
                        @can('task_access')
                        <li><a href="{{ route('tasks.index') }}"
                            class="{{ request()->is('*/tasks*') ? 'active' : '' }}">
                            <x-icon name="square-check" class="w-4 h-4" />
                            {{ trans('cruds.task.title') }}
                        </a></li>
                        @endcan
                        @feature('task_calendar')
                        @can('task_calendar_access')
                        <li><a href="{{ route('task-calendars.index') }}"
                            class="{{ request()->is('*/task-calendars*') ? 'active' : '' }}">
                            <x-icon name="calendar" class="w-4 h-4" />
                            {{ trans('cruds.taskCalendar.title') }}
                        </a></li>
                        @endcan
                        @endfeature
                    </ul>
                </details>
            </li>
            @endcan
            @endfeature

            {{-- FAQ --}}
            @feature('faq')
            @can('faq_management_access')
            <li class="menu-title mt-3 text-[10px] uppercase tracking-widest text-base-content/40 px-2">
                Support
            </li>
            <li>
                <details {{ request()->is('*/faq-*') ? 'open' : '' }}>
                    <summary>
                        <x-icon name="help-circle" class="w-4 h-4" />
                        {{ trans('cruds.faqManagement.title') }}
                    </summary>
                    <ul>
                        @can('faq_category_access')
                        <li><a href="{{ route('faq-categories.index') }}"
                            class="{{ request()->is('*/faq-categories*') ? 'active' : '' }}">
                            <x-icon name="folder" class="w-4 h-4" />
                            {{ trans('cruds.faqCategory.title') }}
                        </a></li>
                        @endcan
                        @can('faq_question_access')
                        <li><a href="{{ route('faq-questions.index') }}"
                            class="{{ request()->is('*/faq-questions*') ? 'active' : '' }}">
                            <x-icon name="message-circle" class="w-4 h-4" />
                            {{ trans('cruds.faqQuestion.title') }}
                        </a></li>
                        @endcan
                    </ul>
                </details>
            </li>
            @endcan
            @endfeature

            {{-- Contacts --}}
            @feature('contact_management')
            @can('contact_management_access')
            <li class="menu-title mt-3 text-[10px] uppercase tracking-widest text-base-content/40 px-2">
                Contacts
            </li>
            <li>
                <details {{ request()->is('*/contact-*') ? 'open' : '' }}>
                    <summary>
                        <x-icon name="phone" class="w-4 h-4" />
                        {{ trans('cruds.contactManagement.title') }}
                    </summary>
                    <ul>
                        @can('contact_company_access')
                        <li><a href="{{ route('contact-companies.index') }}"
                            class="{{ request()->is('*/contact-companies*') ? 'active' : '' }}">
                            <x-icon name="building-skyscraper" class="w-4 h-4" />
                            {{ trans('cruds.contactCompany.title') }}
                        </a></li>
                        @endcan
                        @can('contact_contact_access')
                        <li><a href="{{ route('contact-contacts.index') }}"
                            class="{{ request()->is('*/contact-contacts*') ? 'active' : '' }}">
                            <x-icon name="user-plus" class="w-4 h-4" />
                            {{ trans('cruds.contactContact.title') }}
                        </a></li>
                        @endcan
                    </ul>
                </details>
            </li>
            @endcan
            @endfeature

            {{-- LMS --}}
            @feature('lms')
            <li class="menu-title mt-3 text-[10px] uppercase tracking-widest text-base-content/40 px-2">
                Formation
            </li>
            @feature('lms_courses')
            @can('course_access')
            <li><a href="{{ route('courses.index') }}"
                class="{{ request()->is('*/courses*') ? 'active' : '' }}">
                <x-icon name="school" class="w-4 h-4" />
                {{ trans('cruds.course.title') }}
            </a></li>
            @endcan
            @can('lesson_access')
            <li><a href="{{ route('lessons.index') }}"
                class="{{ request()->is('*/lessons*') ? 'active' : '' }}">
                <x-icon name="book" class="w-4 h-4" />
                {{ trans('cruds.lesson.title') }}
            </a></li>
            @endcan
            @endfeature

            @feature('lms_tests')
            @can('test_access')
            <li><a href="{{ route('tests.index') }}"
                class="{{ request()->is('*/tests*') ? 'active' : '' }}">
                <x-icon name="clipboard-list" class="w-4 h-4" />
                {{ trans('cruds.test.title') }}
            </a></li>
            @endcan
            @can('question_access')
            <li><a href="{{ route('questions.index') }}"
                class="{{ request()->is('*/questions*') ? 'active' : '' }}">
                <x-icon name="help-circle" class="w-4 h-4" />
                {{ trans('cruds.question.title') }}
            </a></li>
            @endcan
            @can('test_result_access')
            <li><a href="{{ route('test-results.index') }}"
                class="{{ request()->is('*/test-results*') ? 'active' : '' }}">
                <x-icon name="chart-bar" class="w-4 h-4" />
                {{ trans('cruds.testResult.title') }}
            </a></li>
            @endcan
            @endfeature
            @endfeature

            {{-- Messages --}}
            @feature('messages')
            <li class="menu-title mt-3 text-[10px] uppercase tracking-widest text-base-content/40 px-2">
                Communication
            </li>
            <li>
                <a href="{{ route('messages.index') }}"
                    class="{{ request()->is('*/messages*') ? 'active' : '' }}">
                    <x-icon name="mail" class="w-4 h-4" />
                    {{ __('global.messages') }}
                    @if(!empty($unreadConversations['all']))
                        <span class="badge badge-error badge-sm ml-auto">{{ $unreadConversations['all'] }}</span>
                    @endif
                </a>
            </li>
            @endfeature

        </ul>
    </nav>

    {{-- Footer sidebar --}}
    <div class="border-t border-base-200 p-2">
        @can('user_management_access')
        <ul class="menu menu-sm w-full">
            <li>
                <a href="{{ route('feature-flags.index') }}"
                    class="{{ request()->is('*/feature-flags*') ? 'active' : '' }}">
                    <x-icon name="toggle-left" class="w-4 h-4" />
                    Features
                </a>
            </li>
            <li>
                <a href="{{ route('settings.index') }}"
                    class="{{ request()->is('*/settings*') ? 'active' : '' }}">
                    <x-icon name="settings" class="w-4 h-4" />
                    Paramètres
                </a>
            </li>
        </ul>
        @endcan
    </div>
</aside>
