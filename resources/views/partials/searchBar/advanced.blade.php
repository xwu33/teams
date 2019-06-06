<tr class="btn-group">
    <td style="border-top: none;">
        <span class="label label-success">Look for </span>
    </td>
    <td style="border-top: none;">
        <input class="form-control" placeholder="Something" name="search{{ $_GET['row'] }}" type="text">
    </td>
    <td style="border-top: none;">
        <span class="label label-success"> in </span>
    </td>
    <td style="border-top: none;">
        <select class="selectpicker btn-primary" name="col{{ $_GET['row'] }}">
            <!-- @foreach ($_GET['cols'] as $col)
                <option>{{ $col }}</option>
            @endforeach -->
        </select>

    </td>
    <td style="border-top: none;">
        <button type="button" class="btn btn-danger remove-searchrow"><i class="glyphicon glyphicon-remove"></i></button>
    </td>
</div>
