<?php
defined( 'ABSPATH' ) || exit;
?>
<table cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" align="center" role="presentation"
       style="border-collapse: collapse; width: 640px; margin: 0 auto; background-color: #ffffff;" width="640">
    <tbody>
    <tr>
        <td style="padding: 0 16px; background-color: #ffffff;">
            <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse: collapse; width: 100%;">
                <tbody>
				<?php foreach ( $todolist as $list ):
					$border_bottom = isset( $list['last'] ) && $list['last'] === true ? 'none' : '1px solid #e0e0e0'; ?>
                    <tr style="border-bottom: <?php echo htmlspecialchars( $border_bottom, ENT_QUOTES, 'UTF-8' ); ?>; line-height: 1.5;">
                        <td style="line-height: 1.5; padding: 10px 0; font-family: Arial, sans-serif; font-size: 14px; color: #333333;">
							<?php echo htmlspecialchars( $list['title'], ENT_QUOTES, 'UTF-8' ); ?>
                        </td>
						<?php if ( $list['status'] === 'pro' ): ?>
                            <td style="width: 130px; text-align: right;">
                                <a href="<?php echo htmlspecialchars( $upgrade_link, ENT_QUOTES, 'UTF-8' ); ?>"
                                   target="_blank"
                                   style="color: #0073aa; text-decoration: underline; font-family: Arial, sans-serif; font-size: 14px;">
                                    <b><?php echo esc_html( __( 'Upgrade to Pro', 'Funnelkit' ) ); ?></b>
                                </a>
                            </td>
						<?php elseif ( $list['status'] === 'active' ): ?>
                            <td style="width: 30px; text-align: right;">
                                <span style="color: #4CAF50; font-size: 18px;">✅</span>
                            </td>
						<?php else: ?>
                            <td style="width: 50px; text-align: right;">
                                <a href="<?php echo htmlspecialchars( $list['link'], ENT_QUOTES, 'UTF-8' ); ?>"
                                   target="_blank"
                                   style="color: #0073aa; text-decoration: underline; font-family: Arial, sans-serif; font-size: 14px;">
                                    <b><?php echo esc_html( __( 'Setup', 'Funnelkit' ) ); ?></b>
                                </a>
                            </td>
						<?php endif; ?>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

