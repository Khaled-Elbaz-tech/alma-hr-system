<?php $__env->startSection('title', 'Team Leave Calendar'); ?>
<?php $__env->startSection('page-title', 'Team Leave Calendar'); ?>

<?php
    // Department color palette (HR theme — no gold)
    $palette = ['#8B251E','#0F766E','#4F46E5','#64748B','#BE123C','#7C3AED','#0369A1','#6D28D9','#15803D','#C2410C'];
    $deptColors = [];
    foreach ($departments as $dept) {
        $deptColors[$dept->id] = $palette[$dept->id % count($palette)];
    }
    $weekDays = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    $firstWeekday = (int) $start->format('w'); // 0 = Sunday
    $todayStr = now()->toDateString();
    // holiday lookup: date => name
    $holidayMap = [];
    foreach ($holidays as $h) {
        $hs = \Carbon\Carbon::parse($h->start_date);
        $he = \Carbon\Carbon::parse($h->end_date);
        for ($d = $hs->copy(); $d->lte($he); $d->addDay()) {
            $holidayMap[$d->toDateString()] = $h->name;
        }
    }
?>

<?php $__env->startSection('content'); ?>
<style>
    .cal-wrap { background:#fff; border-radius:14px; box-shadow:0 2px 10px rgba(0,0,0,.06); overflow:hidden; }
    .cal-toolbar { display:flex; align-items:center; gap:12px; padding:16px 20px; border-bottom:1px solid #E5E7EB; flex-wrap:wrap; }
    .cal-toolbar h3 { margin:0; font-weight:800; font-size:20px; color:#1F2937; }
    .cal-nav-btn { border:1px solid #D1D5DB; background:#fff; border-radius:8px; padding:7px 12px; font-weight:600; color:#374151; text-decoration:none; }
    .cal-nav-btn:hover { background:#F3F4F6; color:#8B251E; }
    .cal-grid { display:grid; grid-template-columns:repeat(7,1fr); }
    .cal-dow { background:#F8FAFC; font-weight:700; font-size:12px; color:#64748B; text-align:center; padding:10px 4px; border-bottom:1px solid #E5E7EB; text-transform:uppercase; letter-spacing:.5px; }
    .cal-day { min-height:110px; border-bottom:1px solid #F1F5F9; border-right:1px solid #F1F5F9; padding:6px; vertical-align:top; }
    .cal-day:nth-child(7n) { border-right:none; }
    .cal-day.other-month { background:#FAFAFA; }
    .cal-day.is-today { background:#FEF2F2; }
    .cal-day.is-weekend { background:#F8FAFC; }
    .cal-date { font-weight:700; font-size:13px; color:#374151; margin-bottom:4px; display:flex; justify-content:space-between; align-items:center; }
    .cal-day.is-today .cal-date span:first-child { background:#8B251E; color:#fff; border-radius:50%; width:24px; height:24px; display:inline-flex; align-items:center; justify-content:center; font-size:12px; }
    .cal-holiday { font-size:10.5px; font-weight:700; color:#fff; background:#8B251E; border-radius:6px; padding:2px 6px; margin-bottom:4px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .cal-chip { font-size:11px; font-weight:600; border-radius:6px; padding:2px 6px; margin-bottom:3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:#fff; }
    .cal-chip.pending { background:#fff !important; border:1.5px dashed; }
    .cal-legend { display:flex; gap:16px; flex-wrap:wrap; padding:14px 20px; border-top:1px solid #E5E7EB; font-size:12.5px; color:#4B5563; }
    .cal-legend .dot { width:12px; height:12px; border-radius:4px; display:inline-block; margin-left:6px; vertical-align:middle; }
    .cal-filter { margin-right:auto; display:flex; gap:8px; align-items:center; }
    .cal-filter select { border:1px solid #D1D5DB; border-radius:8px; padding:7px 10px; font-size:13.5px; }
    @media (max-width:768px){ .cal-day{min-height:70px;} .cal-chip{font-size:9.5px;} }
</style>

<div class="cal-wrap">
    <div class="cal-toolbar">
        <a class="cal-nav-btn" href="<?php echo e(route('leaves.calendar', ['month' => $prevMonth, 'department_id' => $filterDeptId])); ?>">‹ Prev</a>
        <h3><?php echo e($start->format('F Y')); ?></h3>
        <a class="cal-nav-btn" href="<?php echo e(route('leaves.calendar', ['month' => $nextMonth, 'department_id' => $filterDeptId])); ?>">Next ›</a>
        <a class="cal-nav-btn" href="<?php echo e(route('leaves.calendar')); ?>">Today</a>
        <div class="cal-filter">
            <form method="GET" action="<?php echo e(route('leaves.calendar')); ?>" class="d-flex gap-2 align-items-center">
                <input type="hidden" name="month" value="<?php echo e($month); ?>">
                <select name="department_id" onchange="this.form.submit()" <?php echo e($scopedDeptId ? 'disabled' : ''); ?>>
                    <option value="">All Departments</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                        <option value="<?php echo e($dept->id); ?>" <?php echo e((int)$filterDeptId === $dept->id ? 'selected' : ''); ?>><?php echo e($dept->name); ?></option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </select>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($filterDeptId && !$scopedDeptId): ?>
                    <a href="<?php echo e(route('leaves.calendar', ['month' => $month])); ?>" class="cal-nav-btn">Clear</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </form>
        </div>
    </div>

    <div class="cal-grid">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $weekDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="cal-dow"><?php echo e($wd); ?></div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php for($i = 0; $i < $firstWeekday; $i++): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <div class="cal-day other-month"></div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dateStr => $dayLeaves): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php
                $d = \Carbon\Carbon::parse($dateStr);
                $isWeekend = in_array($d->dayOfWeek, [5, 6]); // Fri + Sat
                $isToday = $dateStr === $todayStr;
            ?>
            <div class="cal-day <?php echo e($isToday ? 'is-today' : ''); ?> <?php echo e($isWeekend ? 'is-weekend' : ''); ?>">
                <div class="cal-date"><span><?php echo e($d->day); ?></span></div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($holidayMap[$dateStr])): ?>
                    <div class="cal-holiday" title="<?php echo e($holidayMap[$dateStr]); ?>">🎉 <?php echo e($holidayMap[$dateStr]); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $dayLeaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <?php
                        $empName = $leave->employee->user->name ?? ('#' . $leave->employee_id);
                        $deptId = $leave->employee->department_id;
                        $color = $deptColors[$deptId] ?? '#64748B';
                        $isPending = in_array($leave->status, ['pending', 'hr_approved', 'manager_approved']);
                        $tip = $empName . ' — ' . ($leave->leaveType->name ?? '') . ' (' . $leave->status . ')';
                    ?>
                    <div class="cal-chip <?php echo e($isPending ? 'pending' : ''); ?>"
                         style="<?php echo e($isPending ? "border-color:{$color};color:{$color};" : "background:{$color};"); ?>"
                         title="<?php echo e($tip); ?>"><?php echo e($empName); ?></div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    <div class="cal-legend">
        <span><strong>Departments:</strong></span>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <span><?php echo e($dept->name); ?><span class="dot" style="background:<?php echo e($deptColors[$dept->id]); ?>"></span></span>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        <span style="margin-right:12px;"></span>
        <span><strong>Status:</strong></span>
        <span><span class="dot" style="background:#8B251E"></span> Approved</span>
        <span><span class="dot" style="background:#fff;border:1.5px dashed #64748B"></span> Pending approval</span>
        <span><span class="dot" style="background:#8B251E"></span> 🎉 Public holiday</span>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp1\htdocs\alma-hr-system\resources\views/leaves/calendar.blade.php ENDPATH**/ ?>