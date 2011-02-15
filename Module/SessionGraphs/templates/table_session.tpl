        <div class="expand">
            <h3>Overall</h3>
            <!-- Session details -->
            <table>
            <tbody>
            {foreach from=$session key=i item=field}
            <tr>
                <td>{$field.label}:</td>
                <td><input type="hidden" id="{$field.id}" value="{$field.value}">{$field.value} {$field.units}</td>
            </tr>
            {/foreach}
            </tbody>
            </table>
        </div>
        <br />
