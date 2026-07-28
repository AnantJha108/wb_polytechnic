{{--
Usage:
@include('backend.admin.employee.partials.dynamicYesNoSection', [
'sectionId' => 'qip',
'toggleName' => 'higher_study_qip',
'rowsName' => 'qip_rows',
'label' => 'Whether availed higher study on Full Time (QIP) basis with study leave or not?',
'fields' => [
['name' => 'session', 'label' => 'Session', 'type' => 'text'],
['name' => 'course', 'label' => 'Course', 'type' => 'text'],
['name' => 'institute_name', 'label' => 'Name of the Institute', 'type' => 'text'],
['name' => 'start_date', 'label' => 'Start Date', 'type' => 'date'],
['name' => 'end_date', 'label' => 'End Date (optional)', 'type' => 'date'],
],
'existingRows' => $employee->higherStudies->where('type', 'qip') ?? [],
'defaultYes' => $employee->higher_study_qip ?? 'no',
])
--}}

<div class="border rounded p-3 mb-3">
    <label class="fw-bold d-block mb-2">{{ $label }}</label>

    <div class="form-check form-check-inline">
        <input class="form-check-input yes-no-toggle" type="radio" name="{{ $toggleName }}" value="yes"
            id="{{ $sectionId }}_yes" {{ $defaultYes=='yes' ? 'checked' : '' }} data-target="{{ $sectionId }}_rows">
        <label class="form-check-label" for="{{ $sectionId }}_yes">YES</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input yes-no-toggle" type="radio" name="{{ $toggleName }}" value="no"
            id="{{ $sectionId }}_no" {{ $defaultYes !='yes' ? 'checked' : '' }} data-target="{{ $sectionId }}_rows">
        <label class="form-check-label" for="{{ $sectionId }}_no">NO</label>
    </div>

    <div id="{{ $sectionId }}_rows" class="mt-3" style="{{ $defaultYes == 'yes' ? '' : 'display:none;' }}">
        <div class="dynamic-rows-container" data-section="{{ $sectionId }}" data-max="4">

            @forelse ($existingRows as $i => $row)
            <div class="row g-2 mb-2 dynamic-row align-items-end">
                @foreach ($fields as $field)
                <div class="col">
                    <label class="small text-muted">{{ $field['label'] }}</label>
                    <input type="{{ $field['type'] }}" name="{{ $rowsName }}[{{ $i }}][{{ $field['name'] }}]"
                        class="form-control form-control-sm" value="{{ $row->{$field['name']} ?? '' }}">
                </div>
                @endforeach
                <div class="col-auto">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-dynamic-row">Remove</button>
                </div>
            </div>
            @empty
            <div class="row g-2 mb-2 dynamic-row align-items-end">
                @foreach ($fields as $field)
                <div class="col">
                    <label class="small text-muted">{{ $field['label'] }}</label>
                    <input type="{{ $field['type'] }}" name="{{ $rowsName }}[0][{{ $field['name'] }}]"
                        class="form-control form-control-sm">
                </div>
                @endforeach
                <div class="col-auto">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-dynamic-row"
                        style="display:none;">Remove</button>
                </div>
            </div>
            @endforelse

        </div>

        <button type="button" class="btn btn-sm btn-outline-primary add-more-btn" data-section="{{ $sectionId }}">
            + Add More
        </button>
    </div>
</div>