        <!-- Session Zones -->
        <div class="expand">
            <h3>Zones</h3>
            <table class="tablesorter" id="zones">
            <thead>
            <tr>
                <th>Zone</th>
                <th>Length</th>
            </thead>
            <tbody>
            {foreach from=$zones key=label item=zone}
            <tr>
                <td>{$zone.zone}:</td>
                <td>{$zone.length}</td>
            </tr>
            {foreachelse}
            <tr>
                <td colspan=2>No zones found.</td>
            </tr>
            {/foreach}
            </tbody>
            </table>
        </div>
        <br />
