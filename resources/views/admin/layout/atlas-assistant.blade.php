@if (Auth::check() && in_array((int) Auth::user()->type, [2, 4], true))
    <div class="atlas-assistant" data-atlas-assistant>
        <button type="button" class="atlas-assistant__bubble" data-atlas-toggle>
            <span class="atlas-assistant__bubble-icon">A</span>
            <span class="atlas-assistant__bubble-text">Atlas</span>
        </button>

        <div class="atlas-assistant__panel d-none" data-atlas-panel>
            <div class="atlas-assistant__header">
                <div>
                    <div class="atlas-assistant__eyebrow">Hatchers AI</div>
                    <h5>Atlas</h5>
                </div>
                <button type="button" class="atlas-assistant__close" data-atlas-close>&times;</button>
            </div>

            <div class="atlas-assistant__messages" data-atlas-messages>
                <div class="atlas-assistant__message atlas-assistant__message--assistant">
                    I'm Atlas. Ask me how to build your Bazaar store, add products, manage orders, set up pages, or plan your next move. If you need social content or campaigns, I'll point you to Atlas too.
                </div>
            </div>

            <form class="atlas-assistant__composer" data-atlas-form>
                <textarea
                    class="atlas-assistant__input"
                    data-atlas-input
                    rows="3"
                    placeholder="Ask Atlas anything about your store..."
                ></textarea>
                <button type="submit" class="btn btn-primary atlas-assistant__send" data-atlas-send>Send</button>
            </form>
        </div>
    </div>

    <style>
        .atlas-assistant {
            position: fixed;
            right: 24px;
            bottom: 84px;
            z-index: 1080;
        }

        .atlas-assistant__bubble {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 0;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--bs-primary), #171717);
            color: #fff;
            padding: 12px 16px;
            box-shadow: 0 18px 35px rgba(0, 0, 0, 0.16);
        }

        .atlas-assistant__bubble-icon {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.18);
            font-weight: 700;
        }

        .atlas-assistant__panel {
            width: min(380px, calc(100vw - 32px));
            margin-top: 12px;
            background: #fff;
            border: 1px solid rgba(17, 24, 39, 0.08);
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 28px 60px rgba(15, 23, 42, 0.2);
        }

        .atlas-assistant__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px 12px;
            border-bottom: 1px solid rgba(17, 24, 39, 0.08);
        }

        .atlas-assistant__eyebrow {
            font-size: 11px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #8b7355;
            margin-bottom: 4px;
        }

        .atlas-assistant__header h5 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .atlas-assistant__close {
            border: 0;
            background: transparent;
            color: #6b7280;
            font-size: 26px;
            line-height: 1;
        }

        .atlas-assistant__messages {
            max-height: 360px;
            overflow-y: auto;
            padding: 16px;
            background: #f8f5ee;
        }

        .atlas-assistant__message {
            max-width: 88%;
            padding: 12px 14px;
            border-radius: 16px;
            margin-bottom: 10px;
            white-space: pre-wrap;
            font-size: 14px;
            line-height: 1.5;
        }

        .atlas-assistant__message--assistant {
            background: #fff;
            color: #1f2937;
            border-top-left-radius: 6px;
        }

        .atlas-assistant__message--user {
            margin-left: auto;
            background: rgba(0, 0, 0, 0.92);
            color: #fff;
            border-top-right-radius: 6px;
        }

        .atlas-assistant__composer {
            padding: 14px;
            background: #fff;
            border-top: 1px solid rgba(17, 24, 39, 0.08);
        }

        .atlas-assistant__input {
            width: 100%;
            border: 1px solid rgba(17, 24, 39, 0.12);
            border-radius: 14px;
            padding: 12px 14px;
            resize: none;
            color: #111827;
        }

        .atlas-assistant__send {
            margin-top: 10px;
            width: 100%;
            border-radius: 12px;
        }

        @media (max-width: 767px) {
            .atlas-assistant {
                right: 14px;
                bottom: 74px;
            }
        }
    </style>

    <script>
        $(function () {
            const root = $('[data-atlas-assistant]');
            if (!root.length) {
                return;
            }

            const panel = root.find('[data-atlas-panel]');
            const messages = root.find('[data-atlas-messages]');
            const input = root.find('[data-atlas-input]');
            const send = root.find('[data-atlas-send]');
            const form = root.find('[data-atlas-form]');

            function appendMessage(text, role) {
                const safeText = $('<div>').text(text).html().replace(/\n/g, '<br>');
                messages.append(
                    '<div class="atlas-assistant__message atlas-assistant__message--' + role + '">' + safeText + '</div>'
                );
                messages.scrollTop(messages[0].scrollHeight);
            }

            root.find('[data-atlas-toggle]').on('click', function () {
                panel.toggleClass('d-none');
            });

            root.find('[data-atlas-close]').on('click', function () {
                panel.addClass('d-none');
            });

            form.on('submit', function (event) {
                event.preventDefault();

                const message = $.trim(input.val());
                if (!message) {
                    return;
                }

                appendMessage(message, 'user');
                input.val('');
                send.prop('disabled', true).text('Thinking...');

                $.ajax({
                    url: "{{ URL::to('/admin/atlas-assistant/chat') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        message: message,
                        current_page: window.location.pathname
                    }
                }).done(function (response) {
                    appendMessage(response.reply || "I'm here and ready to help.", 'assistant');
                }).fail(function (xhr) {
                    const error = xhr.responseJSON && xhr.responseJSON.error
                        ? xhr.responseJSON.error
                        : 'Atlas could not answer right now. Please try again.';
                    appendMessage(error, 'assistant');
                }).always(function () {
                    send.prop('disabled', false).text('Send');
                });
            });
        });
    </script>
@endif
