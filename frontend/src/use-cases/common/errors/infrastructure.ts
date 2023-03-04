import { Error } from "./error";

export namespace Infrastructure {
  export class BadRequest implements Error {
    constructor(
      readonly message: string
    ){}
  }

  export class NotAuthorized implements Error {
    constructor(
      readonly message: string
    ){}
  }

  export class InternalServerError implements Error {
    constructor(
      readonly message: string
    ){}
  }

  export class NotFound implements Error {
    constructor(
      readonly message: string
    ){}
  }
}
