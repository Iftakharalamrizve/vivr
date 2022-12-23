import NextLink, { LinkProps as NextLinkProps } from 'next/link';
import React from 'react';

const Link: React.FC<
  NextLinkProps & { children:React.ReactNode , className?: string; title?: string }
> = ({ className, children, ...props }) => {
  return (
    <NextLink {...props}>
      <a className={className}>{children}</a>
    </NextLink>
  );
};

export default Link;
