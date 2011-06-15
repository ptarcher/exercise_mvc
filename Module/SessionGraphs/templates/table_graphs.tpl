        <!-- Google Maps -->
        <div class="expand">
            <h3>Location</h3>
            <div style="margin-top:20px; margin-left:20px; width:620px; height:640px" id="map"></div>
        </div>
        <br />

        <!-- Data graphs -->
{foreach from=$graphs item=graph key=id}
        <div class="expand">
            <h3>{$graph.name}</h3>
            <form>
                <select id="select_graph_{$id}">
{foreach from=$graph.graph_types item=option key=val}
                    <option value="{$val}">{$option}</option>
{/foreach}
                </select>
            </form>
            <div class="jqplot" style="margin:20px;width:620px;height:240px;" id="graph_{$id}"></div>
        </div>
        <br />
{/foreach}
