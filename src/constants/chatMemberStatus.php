<?php

namespace BPT\constants;

/**
 * User status in the chat
 */
class chatMemberStatus {
    /** The creator of the chat */
    public const CREATOR = 'creator';

    /** The admin of the chat */
    public const ADMINISTRATOR = 'administrator';

    /** The member of the chat */
    public const MEMBER = 'member';

    /** Restricted in the chat */
    public const RESTRICTED = 'restricted';

    /** Left or not joined in the chat */
    public const LEFT = 'left';

    /** Kicked in the chat */
    public const KICKED = 'kicked';
}
